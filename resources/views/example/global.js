const firstPath = location.pathname.split('/')[1]

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

window.onresize = function () {
    $("table").each(function () {
        var table_id = $(this).attr("id");
        if ($.fn.DataTable.isDataTable(table_id)) {
            $("#" + table_id).DataTable().ajax.reload();
        }
    });
}

autoSetFilterValue()

$(document).on("click", ".btn-submit", function () {

    /** SPECIAL CASE FOR CKEDIOTR INPUT */
    document.querySelectorAll("[data-text_editor]").forEach(editorElement => {
        if (editorElement.editorInstance) {
            editorElement.editorInstance.updateSourceElement();
        }
    });

    const form = $(this).closest("form");
    const url = form.attr("action");
    const formId = form.attr("id");

    Swal.fire({
        title: "Apakah anda yakin ?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#009EF7",
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        preConfirm: () => {
            Swal.showLoading();

            return new Promise((resolve) => {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: new FormData(document.getElementById(formId)),
                    contentType: false,
                    processData: false,
                    dataType: "JSON",
                    headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
                    success: resp => {
                        if ($.isEmptyObject(resp.errors)) {
                            handleResponse(resp);
                            resolve();
                        } else {
                            Swal.close();
                            let errorsHtml = '';
                            $.each(resp.errors, (key, val) => {
                                $("#" + key + "_error").text(val[0]);
                                errorsHtml += "<li>" + val + "</li>";
                            });
                            errResponse(errorsHtml);
                        }
                    },
                    error: resp => {
                        Swal.close();
                        let errorsHtml = '';
                        if (resp.responseText) {
                            const response = $.parseJSON(resp.responseText);
                            if (response.message) {
                                errorsHtml = response.message;
                            } else if (response.error) {
                                errorsHtml = response.error;
                            } else if (response.errors) {
                                $.each(response.errors, (key, val) => {
                                    $("#" + key + "_error").text(val[0]);
                                    errorsHtml += "<li>" + val + "</li>";
                                });
                            }
                        } else {
                            errorsHtml = 'Something is wrong! Check your connection';
                        }
                        errResponse(errorsHtml);
                    }
                });
            });
        }
    });
})

$(document).on("click", ".btn-delete", function () {
    const url = $(this).data("url");
    Swal.fire({
        title: "Apakah anda yakin?",
        icon: "error",
        text: "Data akan dihapus dari database",
        showCancelButton: true,
        confirmButtonColor: "#009EF7",
        confirmButtonText: "Ya",
        cancelButtonText: "Tidak",
        preConfirm: () => {
            Swal.showLoading();

            return new Promise((resolve) => {
                $.ajax({
                    url: url,
                    type: "DELETE",
                    headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
                    success: resp => {
                        handleResponse(resp);
                        resolve();
                    },
                    error: resp => {
                        Swal.close();
                        errResponse('Something is wrong! Check your connection');
                    }
                });
            });
        }
    });
});

$(document).on('keyup', ".search-box", function () {
    let val = this.value
    var table_id = $(this).data("tableid");
    $("#" + table_id).DataTable().search(val).draw();

    lsName = 'searchbox_' + table_id
    localStorage[lsName] = $(this).val()
})

$(document).on('keyup change', "#filter-div select", function () {
    lsName = firstPath + '_' + $(this).attr('name')
    localStorage[lsName] = $(this).val()
})

function handleResponse(resp) {
    if (resp["reload"]) {
        if (resp["reload"] === true || resp["reload"] == "reload") {
            location.reload();
        } else if (resp["reload"] == "reload_table") {
            $(".modal").modal("hide");
            $("table").each(function () {
                var table_id = $(this).attr("id");
                $("#" + table_id)
                    .DataTable()
                    .ajax.reload(null);
            });
        } else window.location = resp["reload"];
    }

    if (resp["status"] == "error") {
        let msg = resp["msg"];

        if (typeof msg === "object") {
            let errorsHtml = "";
            for (const key in msg) {
                errorsHtml += "<li>" + msg[key] + "</li>";
            }
            errResponse(errorsHtml);
        }
        else
            errResponse(msg);
    }
    else {
        return Swal.fire({
            timer: 1000,
            showConfirmButton: false,
            title: resp["msg"],
            icon: resp["status"],
        });
    }
}

function errResponse(err) {
    if (typeof err === "object") {
        text = err.message + ": <br>" + err.response;
    } else {
        text = err;
    }

    let title = "<h4>Oopps...!</h4>";
    return Swal.fire({
        html: title + text,
        icon: "error",
    });
}

function autoSetFilterValue() {
    var filterDiv = document.getElementById("filter-div");
    if (filterDiv) {
        var selectElements = filterDiv.getElementsByTagName("select");
        let listSelectName = []
        for (var i = 0; i < selectElements.length; i++) {
            selectName = selectElements[i].name
            listSelectName.push(selectName)
            lsName = firstPath + '_' + selectName
            if (localStorage[lsName] && localStorage[lsName] != '') {
                $('select[name=' + selectName + ']').val(localStorage[lsName]).trigger('change')
            }
        }
    }

    var searchbox = document.getElementsByClassName("search-box");
    if (searchbox) {
        for (var i = 0; i < searchbox.length; i++) {
            tableId = searchbox[i].getAttribute('data-tableid')
            if (localStorage['searchbox_' + tableId]) {
                searchbox[i].value = localStorage['searchbox_' + tableId];
            }
        }
    }

}
