@props(['type' => 'sidebar', 'group' => 'sys'])

@php
    // --- 1. ADMIN MENU STRUCTURE ---
    $adminMenu = [
        [
            'type'  => 'item',
            'title' => 'Beranda',
            'route' => 'dashboard',
            'icon'  => 'ti ti-home',
        ],
        [
            'type'          => 'dropdown',
            'title'         => 'Dashboard',
            'id'            => 'navbar-dashboards',
            'icon'          => 'ti ti-layout-dashboard',
            'active_routes' => ['lab.dashboard', 'pemutu.dashboard', 'eoffice.dashboard', 'hr.dashboard', 'pmb.dashboard', 'cbt.dashboard', 'sys.dashboard'],
            'children'      => [
                ['title' => 'Lab', 'route' => 'lab.dashboard', 'icon' => 'ti ti-flask'],
                ['title' => 'Pemutu', 'route' => 'pemutu.dashboard', 'icon' => 'ti ti-checkbox'],
                ['title' => 'E-Office', 'route' => 'eoffice.dashboard', 'icon' => 'ti ti-mail-opened'],
                ['title' => 'HR', 'route' => 'hr.dashboard', 'icon' => 'ti ti-briefcase'],
                ['title' => 'PMB', 'route' => 'pmb.dashboard', 'icon' => 'ti ti-school'],
                ['title' => 'CBT', 'route' => 'cbt.dashboard', 'icon' => 'ti ti-device-laptop'],
                ['title' => 'System', 'route' => 'sys.dashboard', 'icon' => 'ti ti-settings-automation'],
            ],
        ],

        [
            'type'  => 'header',
            'title' => 'Data Utama',
        ],
        [
            'type'          => 'dropdown',
            'title'         => 'Master Data',
            'id'            => 'navbar-master-data',
            'icon'          => 'ti ti-database',
            'active_routes' => ['shared.struktur-organisasi.*', 'hr.pegawai.*', 'lab.mahasiswa.*', 'shared.personil.*'],
            'children'      => [
                [
                    'title' => 'Struktur Organisasi',
                    'route' => 'shared.struktur-organisasi.index',
                    'active_routes' => ['shared.struktur-organisasi.*'],
                    'icon'  => 'ti ti-hierarchy-2',
                ],
                [
                    'title' => 'Pegawai',
                    'route' => 'hr.pegawai.index',
                    'active_routes' => ['hr.pegawai.*'],
                    'icon'  => 'ti ti-users',
                ],
                [
                    'title' => 'Mahasiswa',
                    'route' => 'lab.mahasiswa.index',
                    'active_routes' => ['lab.mahasiswa.*'],
                    'icon'  => 'ti ti-school',
                ],
                [
                    'title' => 'Personil',
                    'route' => 'shared.personil.index',
                    'active_routes' => ['shared.personil.*'],
                    'icon'  => 'ti ti-user-check',
                ],
            ],
        ],

        [
            'type'          => 'dropdown',
            'title'         => 'Info Publik',
            'id'            => 'navbar-info-master',
            'icon'          => 'ti ti-info-circle',
            'active_routes' => ['lab.pengumuman.*', 'lab.berita.*', 'shared.slideshow.*', 'shared.faq.*', 'shared.public-menu.*', 'shared.public-page.*'],
            'children'      => [
                [
                    'title'         => 'Pengumuman',
                    'route'         => 'lab.pengumuman.index',
                    'active_routes' => ['lab.pengumuman.*'],
                    'icon'          => 'ti ti-speakerphone',
                ],
                [
                    'title'         => 'Berita',
                    'route'         => 'lab.berita.index',
                    'active_routes' => ['lab.berita.*'],
                    'icon'          => 'ti ti-news',
                ],
                [
                    'title'         => 'Slideshow',
                    'route'         => 'shared.slideshow.index',
                    'active_routes' => ['shared.slideshow.*'],
                    'icon'          => 'ti ti-presentation',
                ],
                [
                    'title'         => 'FAQ',
                    'route'         => 'shared.faq.index',
                    'active_routes' => ['shared.faq.*'],
                    'icon'          => 'ti ti-help',
                ],
                [
                    'title'         => 'Halaman & Menu',
                    'route'         => 'shared.public-menu.index',
                    'active_routes' => ['shared.public-menu.*', 'shared.public-page.*'],
                    'icon'          => 'ti ti-layout-sidebar',
                ],
            ],
        ],

        [
            'type'  => 'header',
            'title' => 'Modul',
        ],
        [
            'type'          => 'dropdown',
            'title'         => 'Layanan Lab',
            'id'            => 'navbar-services',
            'icon'          => 'ti ti-activity',
            'active_routes' => [
                'lab.labs.*',
                'lab.inventaris.*',
                'lab.kegiatan.*',
                'lab.log-lab.*',
                'lab.surat-bebas.*',
                'lab.laporan-kerusakan.*',
                'lab.log-pc.*',
                'lab.software-requests.*',
                'lab.pengumuman.*',
                'lab.semesters.*',
                'lab.mata-kuliah.*',
                'lab.jadwal.*',
                'lab.periode-request.*'
            ],
            'children'      => [
                [
                    'title'         => 'Master Data',
                    'id'            => 'navbar-lab-master',
                    'icon'          => 'ti ti-database',
                    'active_routes' => ['lab.labs.*', 'lab.inventaris.*', 'lab.semesters.*', 'lab.mata-kuliah.*', 'lab.jadwal.*'],
                    'children'      => [
                        [
                            'title'         => 'Data Lab',
                            'route'         => 'lab.labs.index',
                            'active_routes' => ['lab.labs.*'],
                            'icon'          => 'ti ti-flask',
                        ],
                        [
                            'title'         => 'Data Inventaris',
                            'route'         => 'lab.inventaris.index',
                            'active_routes' => ['lab.inventaris.*'],
                            'icon'          => 'ti ti-package',
                        ],
                        [
                            'title'         => 'Data Semester',
                            'route'         => 'lab.semesters.index',
                            'active_routes' => ['lab.semesters.*'],
                            'icon'          => 'ti ti-calendar-stats',
                        ],
                        [
                            'title'         => 'Data Mata Kuliah',
                            'route'         => 'lab.mata-kuliah.index',
                            'active_routes' => ['lab.mata-kuliah.*'],
                            'icon'          => 'ti ti-book',
                        ],
                        [
                            'title'         => 'Jadwal Perkuliahan',
                            'route'         => 'lab.jadwal.index',
                            'active_routes' => ['lab.jadwal.*'],
                            'icon'          => 'ti ti-calendar-event',
                        ],
                    ],
                ],
                [
                    'title'         => 'Peminjaman Lab',
                    'route'         => 'lab.kegiatan.index',
                    'active_routes' => ['lab.kegiatan.*'],
                    'icon'          => 'ti ti-calendar',
                ],
                [
                    'title'         => 'Log Penggunaan Lab',
                    'route'         => 'lab.log-lab.index',
                    'active_routes' => ['lab.log-lab.*'],
                    'icon'          => 'ti ti-file-time',
                ],
                [
                    'title'         => 'Log Penggunaan PC',
                    'route'         => 'lab.log-pc.index',
                    'active_routes' => ['lab.log-pc.*'],
                    'icon'          => 'ti ti-device-desktop-analytics',
                ],
                [
                    'title'         => 'Surat Bebas Lab',
                    'route'         => 'lab.surat-bebas.index',
                    'active_routes' => ['lab.surat-bebas.*'],
                    'icon'          => 'ti ti-certificate',
                ],
                [
                    'title'         => 'Laporan Kerusakan',
                    'route'         => 'lab.laporan-kerusakan.index',
                    'active_routes' => ['lab.laporan-kerusakan.*'],
                    'icon'          => 'ti ti-report-medical',
                ],
                [
                    'title'         => 'Software Requests',
                    'id'            => 'navbar-software-nested',
                    'icon'          => 'ti ti-device-laptop',
                    'active_routes' => ['lab.software-requests.*', 'lab.periode-request.*'],
                    'children'      => [
                        [
                            'title'         => 'Daftar Pengajuan',
                            'route'         => 'lab.software-requests.index',
                            'active_routes' => ['lab.software-requests.index'],
                            'icon'          => 'ti ti-list',
                        ],
                        [
                            'title'         => 'Periode Pengajuan',
                            'route'         => 'lab.periode-request.index',
                            'active_routes' => ['lab.periode-request.*'],
                            'icon'          => 'ti ti-calendar-stats',
                        ],
                    ],
                ],
            ],
        ],

        [
            'type' => 'dropdown',
            'title' => 'Penjaminan Mutu',
            'icon' => 'ti ti-checkbox',
            'route' => '#',
            'active_routes' => ['pemutu.*'],
            // Removed parent 'can' => 'admin' to allow Auditees to see My KPI / Evaluasi Diri
            'children'      => [
                [
                    'title' => 'Label & Kategori',
                    'route' => 'pemutu.labels.index',
                    'active_routes' => ['pemutu.labels.*', 'pemutu.label-types.*'],
                    'icon' => 'ti ti-tags',
                    'can' => 'admin',
                ],
                [
                    'title' => 'Periode SPMI',
                    'route' => 'pemutu.periode-spmis.index',
                    'active_routes' => ['pemutu.periode-spmis.*'],
                    'icon' => 'ti ti-refresh',
                    'can' => 'admin',
                ],
                [
                    'title' => 'Periode KPI',
                    'route' => 'pemutu.periode-kpis.index',
                    'active_routes' => ['pemutu.periode-kpis.*'],
                    'icon' => 'ti ti-calendar-event',
                    'can' => 'admin',
                ],
                [
                    'title' => 'Tim Mutu',
                    'route' => 'pemutu.tim-mutu.index',
                    'active_routes' => ['pemutu.tim-mutu.*'],
                    'icon' => 'ti ti-users-group',
                    'can' => 'admin',
                ],
                [
                    'title'         => 'Penetapan',
                    'id'            => 'navbar-penetapan',
                    'icon'          => 'ti ti-file-text',
                    'can'           => 'admin',
                    'active_routes' => ['pemutu.dokumens.*', 'pemutu.dokumen-spmi.*', 'pemutu.standar.*', 'pemutu.indikators.*', 'pemutu.renop.*', 'pemutu.indikator-summary.*'],
                    'children'      => [
                        [
                            'title'         => 'Kebijakan',
                            'route'         => 'pemutu.dokumens.index',
                            'active_routes' => ['pemutu.dokumens.*', 'pemutu.dokumen-spmi.*'],
                            'icon'          => 'ti ti-file-certificate',
                            'query'         => ['tabs' => 'kebijakan'],
                        ],
                        [
                            'title'         => 'Standar',
                            'route'         => 'pemutu.dokumens.index',
                            'active_routes' => ['pemutu.dokumens.*', 'pemutu.dokumen-spmi.*'],
                            'icon'          => 'ti ti-book',
                            'query'         => ['tabs' => 'standar'],
                        ],
                        [
                            'title'         => 'Indikator',
                            'route'         => 'pemutu.indikators.index',
                            'active_routes' => ['pemutu.indikators.*', 'pemutu.renop.*'],
                            'icon'          => 'ti ti-target',
                        ],
                        [
                            'title'         => 'Summary Indikator',
                            'id'            => 'navbar-summary-indikator',
                            'icon'          => 'ti ti-table-share',
                            'active_routes' => ['pemutu.indikator-summary.*'],
                            'children'      => [
                                [
                                    'title'         => 'Indikator Standar',
                                    'route'         => 'pemutu.indikator-summary.standar',
                                    'active_routes' => ['pemutu.indikator-summary.standar', 'pemutu.indikator-summary.data-standar'],
                                    'icon'          => 'ti ti-book',
                                ],
                                [
                                    'title'         => 'Indikator Performa (KPI)',
                                    'route'         => 'pemutu.indikator-summary.performa',
                                    'active_routes' => ['pemutu.indikator-summary.performa', 'pemutu.indikator-summary.data-performa'],
                                    'icon'          => 'ti ti-chart-bar',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'title'         => 'Evaluasi',
                    'id'            => 'navbar-evaluasi',
                    'icon'          => 'ti ti-chart-bar',
                    'active_routes' => ['pemutu.evaluasi-diri.*', 'pemutu.evaluasi-kpi.*', 'pemutu.ami.*'],
                    'children'      => [
                        [
                            'title'         => 'Evaluasi Diri',
                            'route'         => 'pemutu.evaluasi-diri.index',
                            'active_routes' => ['pemutu.evaluasi-diri.*'],
                            'icon'          => 'ti ti-clipboard-check',
                        ],
                        [
                            'title'         => 'Evaluasi KPI',
                            'route'         => 'pemutu.evaluasi-kpi.index',
                            'active_routes' => ['pemutu.evaluasi-kpi.*'],
                            'icon'          => 'ti ti-clipboard-data',
                        ],
                        [
                            'title'         => 'Audit Mutu Internal',
                            'route'         => 'pemutu.ami.index',
                            'active_routes' => ['pemutu.ami.*'],
                            'icon'          => 'ti ti-shield-check',
                        ],
                    ],
                ],
                [
                    'title'         => 'Pengendalian',
                    'route'         => 'pemutu.pengendalian.index',
                    'active_routes' => ['pemutu.pengendalian.*'],
                    'icon'          => 'ti ti-settings-check',
                ],

            ],
        ],

        [
            'type'          => 'dropdown',
            'title'         => 'Kegiatan',
            'id'            => 'navbar-event',
            'icon'          => 'ti ti-calendar-star',
            'active_routes' => ['Kegiatan.*'],
            'children'      => [
                [
                    'title'         => 'List Kegiatan',
                    'route'         => 'Kegiatan.Kegiatans.index',
                    'active_routes' => ['Kegiatan.Kegiatans.*'],
                    'icon'          => 'ti ti-calendar-event',
                ],
                [
                    'title'         => 'Manajemen Rapat',
                    'route'         => 'Kegiatan.rapat.index',
                    'active_routes' => ['Kegiatan.rapat.*'],
                    'icon'          => 'ti ti-notes',
                ],
                [
                    'title'         => 'Buku Tamu',
                    'route'         => 'Kegiatan.tamus.index',
                    'active_routes' => ['Kegiatan.tamus.*'],
                    'icon'          => 'ti ti-book',
                ],

            ],
        ],
        [
            'type'          => 'dropdown',
            'title'         => 'HR & Kepegawaian',
            'id'            => 'navbar-hr',
            'icon'          => 'ti ti-briefcase',
            'active_routes' => ['hr.*'],
            'children'      => [
                [
                    'title'         => 'Master Data',
                    'route'         => 'hr.master-status-pegawai.index',
                    'active_routes' => ['hr.master-status-pegawai.*', 'hr.master-status-aktifitas.*', 'hr.jabatan-fungsional.*', 'hr.jenis-izin.*', 'hr.jenis-indisipliner.*', 'hr.jenis-shift.*'],
                    'icon'          => 'ti ti-database',
                ],
                [
                    'title'         => 'Pegawai',
                    'route'         => 'hr.pegawai.index',
                    'active_routes' => ['hr.pegawai.*'],
                    'icon'          => 'ti ti-users',
                ],
                [
                    'title'         => 'Approval Data',
                    'route'         => 'hr.approval.index',
                    'active_routes' => ['hr.approval.*'],
                    'icon'          => 'ti ti-check',
                ],
                [
                    'title'         => 'Perizinan',
                    'route'         => 'hr.perizinan.index',
                    'active_routes' => ['hr.perizinan.*'],
                    'icon'          => 'ti ti-file-certificate',
                ],
                [
                    'title'         => 'Lembur',
                    'route'         => 'hr.lembur.index',
                    'active_routes' => ['hr.lembur.*'],
                    'icon'          => 'ti ti-clock-hour-4',
                ],
                [
                    'title'         => 'Indisipliner',
                    'route'         => 'hr.indisipliner.index',
                    'active_routes' => ['hr.indisipliner.*'],
                    'icon'          => 'ti ti-alert-circle',
                ],

                [
                    'title'         => 'Tanggal Libur',
                    'route'         => 'hr.tanggal-libur.index',
                    'active_routes' => ['hr.tanggal-libur.*'],
                    'icon'          => 'ti ti-calendar-off',
                ],
                [
                    'title'         => 'Mesin Presensi',
                    'route'         => 'hr.att-device.index',
                    'active_routes' => ['hr.att-device.*'],
                    'icon'          => 'ti ti-device-computer-camera',
                ],
                [
                    'title'         => 'Presensi Online',
                    'route'         => 'hr.presensi.index',
                    'active_routes' => ['hr.presensi.*'],
                    'icon'          => 'ti ti-fingerprint',
                    'badge'         => function() {
                        return request()->routeIs('hr.presensi.*');
                    },
                ],
            ],
        ],
        [
            'type'          => 'dropdown',
            'title'         => 'E-Office',
            'id'            => 'navbar-eoffice',
            'icon'          => 'ti ti-mail-opened',
            'active_routes' => ['eoffice.*'],
            'children'      => [
                                [
                    'title'         => 'Master Data',
                    'id'            => 'navbar-eoffice-master',
                    'icon'          => 'ti ti-database',
                    'active_routes' => ['eoffice.master-data.*', 'eoffice.jenis-layanan.*', 'eoffice.kategori-isian.*'],
                    'children'      => [
                        [
                            'title'         => 'Semua Master Data',
                            'route'         => 'eoffice.master-data.index',
                            'active_routes' => ['eoffice.master-data.*'],
                            'icon'          => 'ti ti-list',
                        ],
                        [
                            'title'         => 'Jenis Layanan',
                            'route'         => 'eoffice.jenis-layanan.index',
                            'active_routes' => ['eoffice.jenis-layanan.*'],
                            'icon'          => 'ti ti-category',
                        ],
                        [
                            'title'         => 'Master Isian',
                            'route'         => 'eoffice.kategori-isian.index',
                            'active_routes' => ['eoffice.kategori-isian.*'],
                            'icon'          => 'ti ti-forms',
                        ],
                    ],
                ],
                [
                    'title'         => 'Layanan Saya',
                    'route'         => 'eoffice.layanan.index',
                    'active_routes' => ['eoffice.layanan.*'],
                    'icon'          => 'ti ti-user-check',
                ],
                [
                    'title'         => 'Buat Pengajuan',
                    'route'         => 'eoffice.layanan.services',
                    'icon'          => 'ti ti-plus',
                ],
                [
                    'title'         => 'Feedback',
                    'route'         => 'eoffice.feedback.index',
                    'active_routes' => ['eoffice.feedback.*'],
                    'icon'          => 'ti ti-message',
                ],

            ],
        ],
        [
            'type'          => 'dropdown',
            'title'         => 'Penerimaan (PMB)',
            'id'            => 'navbar-pmb',
            'icon'          => 'ti ti-school',
            'active_routes' => ['pmb.*'],
            'children'      => [
                [
                    'title'         => 'Master Data',
                    'id'            => 'navbar-pmb-master',
                    'icon'          => 'ti ti-database',
                    'active_routes' => ['pmb.periode.*', 'pmb.jalur.*', 'pmb.prodi.*', 'pmb.jenis-dokumen.*'],
                    'children'      => [
                        [
                            'title'         => 'Periode',
                            'route'         => 'pmb.periode.index',
                            'active_routes' => ['pmb.periode.*'],
                            'icon'          => 'ti ti-calendar',
                        ],
                        [
                            'title'         => 'Jalur Pendaftaran',
                            'route'         => 'pmb.jalur.index',
                            'active_routes' => ['pmb.jalur.*'],
                            'icon'          => 'ti ti-map-2',
                        ],
                        [
                            'title'         => 'Jenis Dokumen',
                            'route'         => 'pmb.jenis-dokumen.index',
                            'active_routes' => ['pmb.jenis-dokumen.*'],
                            'icon'          => 'ti ti-file-text',
                        ],
                    ],
                ],
                [
                    'title'         => 'Calon Mahasiswa Baru',
                    'route'         => 'pmb.camaba.index',
                    'active_routes' => ['pmb.camaba.*'],
                    'icon'          => 'ti ti-users',
                ],
                [
                    'title'         => 'Pendaftar',
                    'route'         => 'pmb.pendaftar.index',
                    'active_routes' => ['pmb.pendaftar.*'],
                    'icon'          => 'ti ti-file-text',
                ],
                [
                    'title'         => 'Pembayaran',
                    'route'         => 'pmb.verification.payments',
                    'active_routes' => ['pmb.verification.payments*'],
                    'icon'          => 'ti ti-cash',
                ],
                [
                    'title'         => 'Sesi Ujian',
                    'route'         => 'pmb.sesi-ujian.index',
                    'active_routes' => ['pmb.sesi-ujian.*'],
                    'icon'          => 'ti ti-clock',
                ],
            ],
        ],
        [
            'type'          => 'dropdown',
            'title'         => 'CBT Engine',
            'id'            => 'navbar-cbt',
            'icon'          => 'ti ti-device-laptop',
            'active_routes' => ['cbt.*'],
            'children'      => [
                [
                    'title'         => 'Bank Soal',
                    'route'         => 'cbt.mata-uji.index',
                    'active_routes' => ['cbt.mata-uji.*', 'cbt.soal.*'],
                    'icon'          => 'ti ti-database',
                ],
                [
                    'title'         => 'Paket Ujian',
                    'route'         => 'cbt.paket.index',
                    'active_routes' => ['cbt.paket.*', 'cbt.komposisi.*'],
                    'icon'          => 'ti ti-box',
                ],
                [
                    'title'         => 'Jadwal Ujian',
                    'route'         => 'cbt.jadwal.index',
                    'active_routes' => ['cbt.jadwal.*'],
                    'icon'          => 'ti ti-calendar-event',
                ],
            ],

        ],
        [
            'title' => 'Umpan Balik',
            'route' => 'survei.index',
            'icon'  => 'ti ti-forms',
            'active_routes' => ['survei.*'],
        ],

        // ==========================================
        // 🔹 PROJECT MANAGEMENT MODULE
        // ==========================================
        [
            'title'         => 'Project Management',
            'id'            => 'navbar-projects',
            'icon'          => 'ti ti-layout-dashboard',
            'route'         => 'projects.index',
            'active_routes' => ['projects.*'],

        ],
    ];

    // --- 2. SYS MENU STRUCTURE ---
    $sysMenu = [
        [
            'type'  => 'item',
            'title' => 'Dashboard',
            'route' => 'sys.dashboard',
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>',
        ],
        [
            'type'  => 'header',
            'title' => 'Kontrol Akses',
        ],

            [
                'type' => 'item',
                'title'         => 'Peran (Roles)',
                'route'         => 'sys.roles.index',
                'active_routes' => ['sys.roles.*'],
                'icon'          => 'ti ti-lock-access',
            ],
            [
                'type' => 'item',
                'title'         => 'Izin (Permissions)',
                'route'         => 'sys.permissions.index',
                'active_routes' => ['sys.permissions.*'],
                'icon'          => 'ti ti-shield-lock',
            ],
        [
            'type'  => 'header',
            'title' => 'Log Sistem',
        ],
        [
            'type'          => 'dropdown',
            'title'         => 'System Log',
            'id'            => 'navbar-syslog',
            'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17l0 -5" /><path d="M12 17l0 -1" /><path d="M15 17l0 -3" /></svg>',
            'active_routes' => ['notifications.*', 'activity-log.*', 'sys.error-log.*'],
            'children'      => [
                [
                    'title'         => 'Notifikasi',
                    'route'         => 'notifications.index',
                    'active_routes' => ['notifications.*'],
                    'icon'          => 'ti ti-bell',
                ],
                [
                    'title'         => 'Aktivitas',
                    'route'         => 'activity-log.index',
                    'active_routes' => ['activity-log.*'],
                    'icon'          => 'ti ti-activity',
                ],
                [
                    'title'         => 'Log Error',
                    'route'         => 'sys.error-log.index',
                    'active_routes' => ['sys.error-log.*'],
                    'icon'          => 'ti ti-bug',
                ],
            ],
        ],
        [
            'type'  => 'header',
            'title' => 'Lainnya',
        ],
        [
            'type'          => 'dropdown',
            'title'         => 'Lainnya',
            'id'            => 'navbar-others',
            'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M8 12l.01 0" /><path d="M12 12l.01 0" /><path d="M16 12l.01 0" /></svg>',
            'active_routes' => ['app-config', 'sys.test.*', 'sys.backup.*', 'sys.documentation.*'],
            'children'      => [
                [
                    'title'         => 'Konfigurasi Aplikasi',
                    'route'         => 'app-config',
                    'active_routes' => ['app-config'],
                    'icon'          => 'ti ti-settings',
                ],
                [
                    'title'         => 'Fitur Uji Coba',
                    'route'         => 'sys.test.index',
                    'active_routes' => ['sys.test.*'],
                    'icon'          => 'ti ti-flask-2',
                ],
                [
                    'title'         => 'Manajemen Backup',
                    'route'         => 'sys.backup.index',
                    'active_routes' => ['sys.backup.*'],
                    'icon'          => 'ti ti-database-export',
                ],
                [
                    'title'         => 'Panduan Pengembangan',
                    'route'         => 'sys.documentation.index',
                    'active_routes' => ['sys.documentation.*'],
                    'icon'          => 'ti ti-book-2',
                ],
            ],
        ],
    ];

    // --- SELECTION LOGIC ---
    $menu = ($group === 'admin') ? $adminMenu : $sysMenu;

    // Helper to check active state
    $isActive = function ($routes) {
        if (empty($routes)) return false;
        if (is_array($routes)) {
            foreach ($routes as $route) {
                if (request()->routeIs($route)) return true;
            }
            return false;
        }
        return request()->routeIs($routes);
    };

    // Helper to render icon (SVG or Class)
    $renderIcon = function($icon) {
        if (empty($icon)) return '';
        if (str_contains($icon, '<svg')) {
            return $icon;
        }
        return '<i class="'.$icon.'"></i>';
    };
@endphp

@if($type === 'sidebar')
    <ul class="navbar-nav pt-lg-3">

        @foreach($menu as $item)
            @if(($item['type'] ?? 'item') === 'header')
                <li class="nav-item mt-3">
                    <span class="nav-link disabled text-uppercase text-muted small">{{ $item['title'] ?? '' }}</span>
                </li>
            @elseif(($item['type'] ?? 'item') === 'item')
                <li class="nav-item{{ $isActive($item['route'] ?? null) ? ' active' : '' }}">
                    <a class="nav-link" href="{{ (isset($item['route']) && $item['route'] !== '#') ? route($item['route']) : '#' }}">
                        <span class="nav-link-icon d-lg-inline-block">
                            {!! $renderIcon($item['icon'] ?? '') !!}
                        </span>
                        <span class="nav-link-title">{{ $item['title'] ?? '' }}</span>
                    </a>
                </li>
            @elseif(($item['type'] ?? 'item') === 'dropdown')
                <li class="nav-item dropdown{{ $isActive($item['active_routes'] ?? []) ? ' active' : '' }}">
                    <a class="nav-link dropdown-toggle{{ $isActive($item['active_routes'] ?? []) ? ' show' : '' }}"
                       href="#{{ $item['id'] ?? 'menu-'.Str::random(5) }}"
                       data-bs-toggle="dropdown"
                       data-bs-auto-close="false"
                       role="button"
                       aria-expanded="{{ $isActive($item['active_routes'] ?? []) ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-lg-inline-block">
                            {!! $renderIcon($item['icon'] ?? '') !!}
                        </span>
                        <span class="nav-link-title">{{ $item['title'] ?? '' }}</span>
                    </a>
                    <div class="dropdown-menu{{ $isActive($item['active_routes'] ?? []) ? ' show' : '' }}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                @foreach($item['children'] ?? [] as $child)
                                    @if(($child['type'] ?? 'item') === 'header')
                                        <span class="dropdown-header">{{ $child['title'] ?? '' }}</span>
                                    @elseif(isset($child['children']) && count($child['children']) > 0)
                                        {{-- Recursive Nesting --}}
                                        @php
                                            $isChildActive = $isActive($child['active_routes'] ?? []);
                                        @endphp
                                        <div class="dropend">
                                            <a class="dropdown-item dropdown-toggle{{ $isChildActive ? ' show' : '' }}"
                                               href="#{{ $child['id'] ?? 'submenu-'.Str::random(5) }}"
                                               data-bs-toggle="dropdown"
                                               data-bs-auto-close="false"
                                               role="button"
                                               aria-expanded="{{ $isChildActive ? 'true' : 'false' }}">
                                                @if(!empty($child['icon']))
                                                    {!! $renderIcon($child['icon'], 'icon-inline me-1') !!}
                                                @endif
                                                {{ $child['title'] ?? '' }}
                                            </a>
                                            <div class="dropdown-menu{{ $isChildActive ? ' show' : '' }}">
                                                @foreach($child['children'] as $subchild)
                                                    @php
                                                        $subHref = (isset($subchild['route']) && $subchild['route'] !== '#')
                                                            ? route($subchild['route'], $subchild['query'] ?? [])
                                                            : '#';
                                                        $subIsActive = !empty($subchild['query'])
                                                            ? $isActive($subchild['active_routes'] ?? []) && collect($subchild['query'])->every(fn($v, $k) => request($k) == $v)
                                                            : $isActive($subchild['active_routes'] ?? $subchild['route'] ?? null);
                                                    @endphp
                                                    <a class="dropdown-item{{ $subIsActive ? ' active' : '' }}"
                                                       href="{{ $subHref }}">
                                                        @if(!empty($subchild['icon']))
                                                            {!! $renderIcon($subchild['icon'], 'icon-inline me-1') !!}
                                                        @endif
                                                        {{ $subchild['title'] ?? '' }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        @php
                                            $childHref = (isset($child['route']) && $child['route'] !== '#')
                                                ? route($child['route'], $child['query'] ?? [])
                                                : '#';
                                            $childIsActive = !empty($child['query'])
                                                ? $isActive($child['active_routes'] ?? []) && collect($child['query'])->every(fn($v, $k) => request($k) == $v)
                                                : $isActive($child['active_routes'] ?? $child['route'] ?? '');
                                        @endphp
                                        <a class="dropdown-item{{ $childIsActive ? ' active' : '' }}"
                                           href="{{ $childHref }}">
                                            {{-- Icons optional in submenus --}}
                                            @if(!empty($child['icon']))
                                                {!! $renderIcon($child['icon'], 'icon-inline me-1') !!}
                                            @endif
                                            {{ $child['title'] ?? '' }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </li>
            @endif
        @endforeach
    </ul>

@elseif($type === 'navbar')
    <ul class="navbar-nav">
        @foreach($menu as $item)
            @if(($item['type'] ?? 'item') === 'item')
                <li class="nav-item{{ $isActive($item['route'] ?? null) ? ' active' : '' }}">
                    <a class="nav-link" href="{{ (isset($item['route']) && $item['route'] !== '#') ? route($item['route']) : '#' }}">
                        <span class="nav-link-icon d-lg-inline-block">
                            {!! $renderIcon($item['icon'] ?? '') !!}
                        </span>
                        <span class="nav-link-title">{{ $item['title'] ?? '' }}</span>
                    </a>
                </li>
            @elseif(($item['type'] ?? 'item') === 'dropdown')
                <li class="nav-item dropdown{{ $isActive($item['active_routes'] ?? []) ? ' active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#{{ $item['id'] ?? 'nav-drop' }}" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                        <span class="nav-link-icon d-lg-inline-block">
                            {!! $renderIcon($item['icon'] ?? '') !!}
                        </span>
                        <span class="nav-link-title">{{ $item['title'] ?? '' }}</span>
                    </a>
                    <div class="dropdown-menu">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                @foreach($item['children'] ?? [] as $child)
                                    @if(($child['type'] ?? 'item') === 'header')
                                         <h6 class="dropdown-header">{{ $child['title'] ?? '' }}</h6>
                                    @elseif(isset($child['children']) && count($child['children']) > 0)
                                        @php $isChildActive = $isActive($child['active_routes'] ?? []); @endphp
                                        <div class="dropend">
                                            <a class="dropdown-item dropdown-toggle{{ $isChildActive ? ' show' : '' }}"
                                               href="javascript:void(0)"
                                               data-bs-toggle="dropdown"
                                               data-bs-auto-close="outside"
                                               role="button"
                                               aria-expanded="{{ $isChildActive ? 'true' : 'false' }}">
                                                @if(!empty($child['icon']))
                                                   {!! $renderIcon($child['icon'], 'icon-inline me-1') !!}
                                                @endif
                                                {{ $child['title'] ?? '' }}
                                            </a>
                                            <div class="dropdown-menu{{ $isChildActive ? ' show' : '' }}">
                                                @foreach($child['children'] as $subchild)
                                                    <a class="dropdown-item{{ $isActive($subchild['route'] ?? null) ? ' active' : '' }}"
                                                       href="{{ (isset($subchild['route']) && $subchild['route'] !== '#') ? route($subchild['route']) : '#' }}">
                                                        @if(!empty($subchild['icon']))
                                                            {!! $renderIcon($subchild['icon'], 'icon-inline me-1') !!}
                                                        @endif
                                                        {{ $subchild['title'] ?? '' }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <a class="dropdown-item{{ $isActive($child['active_routes'] ?? $child['route'] ?? '') ? ' active' : '' }}"
                                           href="{{ (isset($child['route']) && $child['route'] !== '#') ? route($child['route']) : '#' }}">
                                            @if(!empty($child['icon']))
                                               {!! $renderIcon($child['icon'], 'icon-inline me-1') !!}
                                            @endif
                                            {{ $child['title'] ?? '' }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </li>
            @endif
        @endforeach
    </ul>
@endif
