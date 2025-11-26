@props([
    'name' => '',
    'updateUrl' => ''
])
<?php
    $updateUrl = route($name.'.update');
?>
@push('scripts')
    <script>
        $(document).on('click', '.btn-{{$name}}', function() {
            let task = $(this).data('task');
            let url  = $(this).data('url');
            let idModal = '{{$name}}-modal'
            let idForm  = '{{$name}}-form'
            if (task == 'add') {
                action = url
                $('#'+idModal+' #icon-form').removeClass('fa-edit text-primary').addClass('fa-plus text-success')
                $('#'+idModal+' .btn-submit').removeClass('btn-primary').addClass('btn-success')


                if (typeof addResponse === 'function') {
                    addResponse()
                }

            } else if (task == 'edit') {
                action = "{{ $updateUrl }}"
                $('#'+idModal+' #icon-form').removeClass('fa-plus text-success').addClass('fa-edit text-primary')
                $('#'+idModal+' .btn-submit').removeClass('btn-success').addClass('btn-primary')

                axios.get(url)
                    .then(resp => {
                        data = resp.data[0]
                        editResponse(idForm,data)
                    })
                    .catch(err => {
                        return errResponse(err)
                    })
            }

            $('#'+idModal+' #'+idForm).attr('action', action).trigger("reset");
            $('#'+idModal).modal('show')
        });
    </script>
@endpush
