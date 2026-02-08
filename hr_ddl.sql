-- DROP SCHEMA dbo;

CREATE SCHEMA dbo;
-- hr.dbo.[__done_sync] definition

-- Drop table

-- DROP TABLE hr.dbo.[__done_sync];

CREATE TABLE [__done_sync] (
	id int IDENTITY(1,1) NOT NULL,
	model varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	model_id varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT [__done_sync_PK] PRIMARY KEY (id)
);


-- hr.dbo.activity_log definition

-- Drop table

-- DROP TABLE hr.dbo.activity_log;

CREATE TABLE activity_log (
	activitylog_id bigint IDENTITY(1,1) NOT NULL,
	user_name varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	[role] varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	[action] nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	description varchar(MAX) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	created_at datetime NULL,
	created_by int NULL,
	updated_at datetime NULL,
	updated_by int NULL,
	deleted_at datetime NULL,
	deleted_by int NULL,
	CONSTRAINT PK__activity__3F523A365A51E775 PRIMARY KEY (activitylog_id)
);


-- hr.dbo.att_cancel definition

-- Drop table

-- DROP TABLE hr.dbo.att_cancel;

CREATE TABLE att_cancel (
	attcancel_id int IDENTITY(1,1) NOT NULL,
	pegawai_shift_id int NULL,
	keterangan_anulir varchar(450) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	set_masuk time NULL,
	set_pulang time NULL,
	jenis_anulir varchar(25) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT PK_anulir_presensi PRIMARY KEY (attcancel_id)
);


-- hr.dbo.att_device definition

-- Drop table

-- DROP TABLE hr.dbo.att_device;

CREATE TABLE att_device (
	attdevice_id int IDENTITY(1,1) NOT NULL,
	nama varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	keterangan varchar(200) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	ip varchar(20) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	sn varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	additional_value text COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	act_code_personel varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	act_code_kitaserver varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	old_id varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT PK__att_devi__C0A84825C5914B68 PRIMARY KEY (attdevice_id)
);


-- hr.dbo.att_log definition

-- Drop table

-- DROP TABLE hr.dbo.att_log;

CREATE TABLE att_log (
	att_log_id int IDENTITY(1,1) NOT NULL,
	sn varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	scan_date datetime NULL,
	pin varchar(32) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	verifymode int NULL,
	inoutmode int NULL,
	status_check varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	date_input datetime NULL,
	device_ip varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	inisial varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deviceName varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	authDate date NULL,
	authTime time(0) NULL,
	ID varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	created_at datetime DEFAULT getdate() NULL,
	updated_at datetime NULL,
	deleted_at datetime NULL,
	CONSTRAINT PK_att_log PRIMARY KEY (att_log_id)
);
 CREATE NONCLUSTERED INDEX att_log_inisial_IDX ON hr.dbo.att_log (  inisial ASC  )  
	 WITH (  PAD_INDEX = OFF ,FILLFACTOR = 100  ,SORT_IN_TEMPDB = OFF , IGNORE_DUP_KEY = OFF , STATISTICS_NORECOMPUTE = OFF , ONLINE = OFF , ALLOW_ROW_LOCKS = ON , ALLOW_PAGE_LOCKS = ON  )
	 ON [PRIMARY ] ;
 CREATE NONCLUSTERED INDEX att_log_scan_date_IDX ON hr.dbo.att_log (  scan_date ASC  )  
	 WITH (  PAD_INDEX = OFF ,FILLFACTOR = 100  ,SORT_IN_TEMPDB = OFF , IGNORE_DUP_KEY = OFF , STATISTICS_NORECOMPUTE = OFF , ONLINE = OFF , ALLOW_ROW_LOCKS = ON , ALLOW_PAGE_LOCKS = ON  )
	 ON [PRIMARY ] ;


-- hr.dbo.departemen definition

-- Drop table

-- DROP TABLE hr.dbo.departemen;

CREATE TABLE departemen (
	departemen_id int IDENTITY(1,1) NOT NULL,
	departemen varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	jurusan_id int NULL,
	is_active smallint NULL,
	alias varchar(25) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	abbr varchar(10) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT PK__departem__EDE1456876DE993B PRIMARY KEY (departemen_id)
);


-- hr.dbo.error_log definition

-- Drop table

-- DROP TABLE hr.dbo.error_log;

CREATE TABLE error_log (
	errorlog_id int IDENTITY(1,1) NOT NULL,
	user_id int NULL,
	[method] nvarchar(6) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	url nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	user_agent nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	header nvarchar(MAX) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	payload nvarchar(MAX) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	created_at datetime NULL,
	updated_at datetime NULL,
	CONSTRAINT PK__error_lo__CC88E87AECEDDEDE PRIMARY KEY (errorlog_id)
);


-- hr.dbo.failed_jobs definition

-- Drop table

-- DROP TABLE hr.dbo.failed_jobs;

CREATE TABLE failed_jobs (
	id bigint IDENTITY(1,1) NOT NULL,
	uuid nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	[connection] nvarchar(MAX) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	queue nvarchar(MAX) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	payload nvarchar(MAX) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	[exception] nvarchar(MAX) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	failed_at datetime DEFAULT getdate() NOT NULL,
	CONSTRAINT PK__failed_j__3213E83F5E468F93 PRIMARY KEY (id)
);
 CREATE UNIQUE NONCLUSTERED INDEX failed_jobs_uuid_unique ON hr.dbo.failed_jobs (  uuid ASC  )  
	 WITH (  PAD_INDEX = OFF ,FILLFACTOR = 100  ,SORT_IN_TEMPDB = OFF , IGNORE_DUP_KEY = OFF , STATISTICS_NORECOMPUTE = OFF , ONLINE = OFF , ALLOW_ROW_LOCKS = ON , ALLOW_PAGE_LOCKS = ON  )
	 ON [PRIMARY ] ;


-- hr.dbo.file_pegawai definition

-- Drop table

-- DROP TABLE hr.dbo.file_pegawai;

CREATE TABLE file_pegawai (
	filepegawai_id int IDENTITY(1,1) NOT NULL,
	jenisfile_id int NULL,
	pegawai_id int NULL,
	keterangan text COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	file_name varchar(200) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	tgl varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	status int NULL,
	created_at datetime NULL,
	created_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT PK_file_pegawai PRIMARY KEY (filepegawai_id)
);


-- hr.dbo.golongan_inpassing definition

-- Drop table

-- DROP TABLE hr.dbo.golongan_inpassing;

CREATE TABLE golongan_inpassing (
	gol_inpassing_id int IDENTITY(1,1) NOT NULL,
	nama_pangkat varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	golongan varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	ruang varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	status int NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	golongan_full varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT PK_golongan_inpassing PRIMARY KEY (gol_inpassing_id)
);


-- hr.dbo.indisipliner definition

-- Drop table

-- DROP TABLE hr.dbo.indisipliner;

CREATE TABLE indisipliner (
	indisipliner_id int IDENTITY(1,1) NOT NULL,
	jenisindisipliner_id int NULL,
	keterangan text COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	tgl_indisipliner date NULL,
	file_pendukung varchar(250) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT PK_indisipliner PRIMARY KEY (indisipliner_id)
);


-- hr.dbo.indisipliner_pegawai definition

-- Drop table

-- DROP TABLE hr.dbo.indisipliner_pegawai;

CREATE TABLE indisipliner_pegawai (
	indispegawai_id int IDENTITY(1,1) NOT NULL,
	indisipliner_id int NULL,
	pegawai_id int NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT PK_indispiliner_pegawai PRIMARY KEY (indispegawai_id)
);


-- hr.dbo.jabatan_fungsional definition

-- Drop table

-- DROP TABLE hr.dbo.jabatan_fungsional;

CREATE TABLE jabatan_fungsional (
	jabfungsional_id int IDENTITY(1,1) NOT NULL,
	kode_jabatan varchar(5) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	jabfungsional varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	is_active int NULL,
	created_at datetime DEFAULT '1' NULL,
	created_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	old_id int NULL,
	tunjangan int NULL,
	CONSTRAINT PK_jabatan_akademik PRIMARY KEY (jabfungsional_id)
);


-- hr.dbo.jabatan_struktural definition

-- Drop table

-- DROP TABLE hr.dbo.jabatan_struktural;

CREATE TABLE jabatan_struktural (
	jabstruktural_id int IDENTITY(1,1) NOT NULL,
	kode_jabatan varchar(5) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	jabstruktural varchar(250) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	abbr varchar(20) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	alias varchar(20) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	kelompok_jabatan varchar(25) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	prodi_id int NULL,
	departemen_id int NULL,
	is_active int NULL,
	created_at datetime DEFAULT '1' NULL,
	created_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	old_id int NULL,
	tunjangan int NULL,
	CONSTRAINT PK_jabatan_struktural PRIMARY KEY (jabstruktural_id)
);


-- hr.dbo.jadwal_pmk definition

-- Drop table

-- DROP TABLE hr.dbo.jadwal_pmk;

CREATE TABLE jadwal_pmk (
	jadwalpmk_id int IDENTITY(1,1) NOT NULL,
	periode int NULL,
	status_rencana_kerja varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	waktu_mulai datetime NULL,
	waktu_selesai datetime NULL,
	komentar_buka datetime NULL,
	komentar_tutup datetime NULL,
	bagian5 int NULL,
	bagian6 int NULL,
	bagian7 int NULL,
	bagian3 int NULL,
	bagian8 int NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	old_id int NULL,
	CONSTRAINT PK_pmp_jadwal PRIMARY KEY (jadwalpmk_id)
);


-- hr.dbo.jadwal_wfh definition

-- Drop table

-- DROP TABLE hr.dbo.jadwal_wfh;

CREATE TABLE jadwal_wfh (
	jadwalwfh_id int IDENTITY(1,1) NOT NULL,
	tgl_mulai date NULL,
	tgl_selesai date NULL,
	jenis_pengisi varchar(20) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	pegawai_id int NULL,
	keterangan text COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	created_at datetime NULL,
	created_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT PK_jadwal_wfh PRIMARY KEY (jadwalwfh_id)
);


-- hr.dbo.jenis_file definition

-- Drop table

-- DROP TABLE hr.dbo.jenis_file;

CREATE TABLE jenis_file (
	jenisfile_id int IDENTITY(1,1) NOT NULL,
	jenisfile varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	is_active int NULL,
	created_at datetime NULL,
	created_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT PK_jenis_file PRIMARY KEY (jenisfile_id)
);


-- hr.dbo.jenis_indisipliner definition

-- Drop table

-- DROP TABLE hr.dbo.jenis_indisipliner;

CREATE TABLE jenis_indisipliner (
	jenisindisipliner_id int IDENTITY(1,1) NOT NULL,
	jenis_indisipliner varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	created_at datetime NULL,
	updated_at datetime NULL,
	deleted_at datetime NULL,
	CONSTRAINT PK_jenis_indisipliner PRIMARY KEY (jenisindisipliner_id)
);


-- hr.dbo.jenis_izin definition

-- Drop table

-- DROP TABLE hr.dbo.jenis_izin;

CREATE TABLE jenis_izin (
	jenisizin_id int IDENTITY(1,1) NOT NULL,
	nama varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	kategori varchar(10) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	max_hari int NULL,
	pemilihan_waktu varchar(20) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	urutan_approval text COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	is_active int NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	old_id int NULL,
	CONSTRAINT PK_Table_1 PRIMARY KEY (jenisizin_id)
);


-- hr.dbo.jenis_shift definition

-- Drop table

-- DROP TABLE hr.dbo.jenis_shift;

CREATE TABLE jenis_shift (
	jenisshift_id int IDENTITY(1,1) NOT NULL,
	nama_shift varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	jadwal_awal_masuk time NULL,
	jadwal_masuk time NULL,
	jadwal_akhir_masuk time NULL,
	jadwal_awal_pulang time NULL,
	jadwal_pulang time NULL,
	jadwal_akhir_pulang time NULL,
	jenis_pegawai varchar(15) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	jenis_hari varchar(15) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	is_active int NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	old_id int NULL,
	CONSTRAINT PK__shift__7B26722048407916 PRIMARY KEY (jenisshift_id)
);


-- hr.dbo.kelas definition

-- Drop table

-- DROP TABLE hr.dbo.kelas;

CREATE TABLE kelas (
	kelas_id int NOT NULL,
	kelas int NULL,
	jml_cuti int NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL
);


-- hr.dbo.keluarga definition

-- Drop table

-- DROP TABLE hr.dbo.keluarga;

CREATE TABLE keluarga (
	keluarga_id int IDENTITY(1,1) NOT NULL,
	pegawai_id int NULL,
	nama varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	hubungan varchar(20) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	alamat text COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	tgl_lahir date NULL,
	jenis_kelamin varchar(5) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	telp varchar(20) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	asuransi varchar(20) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	file_pendukung varchar(200) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	latest_riwayatapproval_id int NULL,
	before_id int NULL,
	is_removed int NULL,
	CONSTRAINT PK_pegawai_keluarga PRIMARY KEY (keluarga_id)
);


-- hr.dbo.lembur definition

-- Drop table

-- DROP TABLE hr.dbo.lembur;

CREATE TABLE lembur (
	lembur_id int IDENTITY(1,1) NOT NULL,
	pengusul int NULL,
	uraian_pekerjaan varchar(MAX) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	alasan varchar(MAX) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	bayar varchar(10) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	latest_riwayatapproval_id int NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	jenis_form varchar(25) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	old_id int NULL,
	CONSTRAINT PK_lembur PRIMARY KEY (lembur_id)
);


-- hr.dbo.lembur_waktu definition

-- Drop table

-- DROP TABLE hr.dbo.lembur_waktu;

CREATE TABLE lembur_waktu (
	lemburwaktu_id int IDENTITY(1,1) NOT NULL,
	lembur_id int NOT NULL,
	tgl_pelaksanaan date NULL,
	jam_mulai time NULL,
	jam_akhir time NULL,
	durasi int NULL,
	bayar varchar(10) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT PK_lembur_waktu PRIMARY KEY (lemburwaktu_id)
);


-- hr.dbo.lembur_waktu_pegawai definition

-- Drop table

-- DROP TABLE hr.dbo.lembur_waktu_pegawai;

CREATE TABLE lembur_waktu_pegawai (
	lemburwaktupegawai_id int IDENTITY(1,1) NOT NULL,
	lemburwaktu_id int NOT NULL,
	pegawai_id int NULL,
	hitung_dengan_gaji int NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT PK_lembur_waktu_pegawai PRIMARY KEY (lemburwaktupegawai_id)
);


-- hr.dbo.log_pekerjaan definition

-- Drop table

-- DROP TABLE hr.dbo.log_pekerjaan;

CREATE TABLE log_pekerjaan (
	logpekerjaan_id int IDENTITY(1,1) NOT NULL,
	jadwalwfh_id int NULL,
	pegawai_id int NULL,
	judul varchar(500) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	kategori_kegiatan int NULL,
	tgl_pelaksanaan date NULL,
	jam_mulai time NULL,
	jam_selesai time NULL,
	target text COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	progress text COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	status_target varchar(15) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	latitude varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	longitude varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	lokasi_pengisian text COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	accuracy varchar(200) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	created_at datetime NULL,
	created_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT PK_log_pekerjaan PRIMARY KEY (logpekerjaan_id)
);


-- hr.dbo.mailbox definition

-- Drop table

-- DROP TABLE hr.dbo.mailbox;

CREATE TABLE mailbox (
	mailbox_id int IDENTITY(1,1) NOT NULL,
	mail_to varchar(800) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	cc_to varchar(800) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	subject varchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	isi text COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	tgl_send datetime NULL,
	mail_status varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	sistem varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	status int NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT PK_mailbox PRIMARY KEY (mailbox_id)
);


-- hr.dbo.migrations definition

-- Drop table

-- DROP TABLE hr.dbo.migrations;

CREATE TABLE migrations (
	id int IDENTITY(1,1) NOT NULL,
	migration nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	batch int NOT NULL,
	CONSTRAINT PK__migratio__3213E83F24400C4A PRIMARY KEY (id)
);


-- hr.dbo.nilai_prestasi_tahunan definition

-- Drop table

-- DROP TABLE hr.dbo.nilai_prestasi_tahunan;

CREATE TABLE nilai_prestasi_tahunan (
	npt_id int IDENTITY(1,1) NOT NULL,
	periode int NULL,
	pegawai_id int NULL,
	nilai float NULL,
	nilai_huruf varchar(5) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	promosi varchar(20) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	is_show int NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT PK__nilai_pr__462FB74429839113 PRIMARY KEY (npt_id)
);


-- hr.dbo.pegawai definition

-- Drop table

-- DROP TABLE hr.dbo.pegawai;

CREATE TABLE pegawai (
	pegawai_id int IDENTITY(1,1) NOT NULL,
	latest_riwayatdatadiri_id int NULL,
	atasan1 int NULL,
	atasan2 int NULL,
	latest_riwayatstatpegawai_id int NULL,
	latest_riwayatstataktifitas_id int NULL,
	latest_riwayatkelas_id int NULL,
	latest_riwayatinpassing_id int NULL,
	latest_riwayatpendidikan_id int NULL,
	latest_riwayatjabfungsional_id int NULL,
	created_at datetime NULL,
	created_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT PK_pegawai PRIMARY KEY (pegawai_id)
);


-- hr.dbo.pegawai_shift definition

-- Drop table

-- DROP TABLE hr.dbo.pegawai_shift;

CREATE TABLE pegawai_shift (
	pegawai_shift_id int IDENTITY(1,1) NOT NULL,
	pegawai_id int NULL,
	jenisshift_id int NULL,
	tgl_shift date NULL,
	keterangan_shift varchar(250) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	[_old_id] int NULL,
	status int NULL,
	[_shift_id] int NULL,
	[_keterangan_anulir] varchar(250) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	[_tgl_pengajuan_anulir] datetime NULL,
	[_pejabat_anulir] int NULL,
	[_status_anulir] varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	[_tgl_perubahan_anulir] datetime NULL,
	[_cek_masuk] varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	[_cek_pulang] varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	[_surattugas_id] int NULL,
	[_jenis_anulir] varchar(25) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	[_set_masuk] time(0) NULL,
	[_set_pulang] time(0) NULL,
	CONSTRAINT PK_pegawai_shift PRIMARY KEY (pegawai_shift_id)
);


-- hr.dbo.pengembangan_diri definition

-- Drop table

-- DROP TABLE hr.dbo.pengembangan_diri;

CREATE TABLE pengembangan_diri (
	pengembangandiri_id int IDENTITY(1,1) NOT NULL,
	pegawai_id int NULL,
	jenis_kegiatan varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	nama_penyelenggara varchar(350) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	peran varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	nama_kegiatan varchar(250) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	tgl_mulai date NULL,
	tgl_selesai date NULL,
	berlaku_hingga date NULL,
	keterangan text COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	file_pendukung varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	latest_riwayatapproval_id int NULL,
	before_id int NULL,
	CONSTRAINT PK_pengembangan_diri PRIMARY KEY (pengembangandiri_id)
);


-- hr.dbo.perizinan definition

-- Drop table

-- DROP TABLE hr.dbo.perizinan;

CREATE TABLE perizinan (
	perizinan_id int IDENTITY(1,1) NOT NULL,
	jenisizin_id int NULL,
	pengusul int NULL,
	pekerjaan_ditinggalkan varchar(500) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	keterangan text COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	alamat_izin text COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	file_pendukung varchar(200) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	tgl_awal date NULL,
	tgl_akhir date NULL,
	jam_awal time NULL,
	jam_akhir time NULL,
	list_tgl_tidakmasuk text COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	uang_cuti int NULL,
	latest_riwayatapproval_id int NULL,
	created_at datetime NULL,
	created_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	uang_cuti_bayar int NULL,
	keluarga_id int NULL,
	periode int NULL,
	CONSTRAINT PK_perizinan PRIMARY KEY (perizinan_id)
);


-- hr.dbo.permissions definition

-- Drop table

-- DROP TABLE hr.dbo.permissions;

CREATE TABLE permissions (
	id bigint IDENTITY(1,1) NOT NULL,
	name nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	guard_name nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	category varchar(30) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	category_sub varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	created_at datetime NULL,
	updated_at datetime NULL,
	CONSTRAINT PK__permissi__3213E83F289A4206 PRIMARY KEY (id)
);
 CREATE UNIQUE NONCLUSTERED INDEX permissions_name_guard_name_unique ON hr.dbo.permissions (  name ASC  , guard_name ASC  )  
	 WITH (  PAD_INDEX = OFF ,FILLFACTOR = 100  ,SORT_IN_TEMPDB = OFF , IGNORE_DUP_KEY = OFF , STATISTICS_NORECOMPUTE = OFF , ONLINE = OFF , ALLOW_ROW_LOCKS = ON , ALLOW_PAGE_LOCKS = ON  )
	 ON [PRIMARY ] ;


-- hr.dbo.personal_access_tokens definition

-- Drop table

-- DROP TABLE hr.dbo.personal_access_tokens;

CREATE TABLE personal_access_tokens (
	id bigint IDENTITY(1,1) NOT NULL,
	tokenable_type nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	tokenable_id bigint NOT NULL,
	name nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	token nvarchar(64) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	abilities nvarchar(MAX) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	last_used_at datetime NULL,
	expires_at datetime NULL,
	created_at datetime NULL,
	updated_at datetime NULL,
	CONSTRAINT PK__personal__3213E83FC0F63A9C PRIMARY KEY (id)
);
 CREATE UNIQUE NONCLUSTERED INDEX personal_access_tokens_token_unique ON hr.dbo.personal_access_tokens (  token ASC  )  
	 WITH (  PAD_INDEX = OFF ,FILLFACTOR = 100  ,SORT_IN_TEMPDB = OFF , IGNORE_DUP_KEY = OFF , STATISTICS_NORECOMPUTE = OFF , ONLINE = OFF , ALLOW_ROW_LOCKS = ON , ALLOW_PAGE_LOCKS = ON  )
	 ON [PRIMARY ] ;
 CREATE NONCLUSTERED INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON hr.dbo.personal_access_tokens (  tokenable_type ASC  , tokenable_id ASC  )  
	 WITH (  PAD_INDEX = OFF ,FILLFACTOR = 100  ,SORT_IN_TEMPDB = OFF , IGNORE_DUP_KEY = OFF , STATISTICS_NORECOMPUTE = OFF , ONLINE = OFF , ALLOW_ROW_LOCKS = ON , ALLOW_PAGE_LOCKS = ON  )
	 ON [PRIMARY ] ;


-- hr.dbo.posisi definition

-- Drop table

-- DROP TABLE hr.dbo.posisi;

CREATE TABLE posisi (
	posisi_id int IDENTITY(1,1) NOT NULL,
	posisi varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	alias varchar(30) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	is_active int NULL,
	created_at datetime NULL,
	created_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	old_id int NULL,
	CONSTRAINT PK_jabatan PRIMARY KEY (posisi_id)
);


-- hr.dbo.riwayat_approval definition

-- Drop table

-- DROP TABLE hr.dbo.riwayat_approval;

CREATE TABLE riwayat_approval (
	riwayatapproval_id int IDENTITY(1,1) NOT NULL,
	model varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	model_id int NULL,
	status varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	pejabat varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	jenis_jabatan varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	keterangan varchar(350) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	created_by_email varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT PK_riwayat_approval PRIMARY KEY (riwayatapproval_id)
);


-- hr.dbo.riwayat_atasan definition

-- Drop table

-- DROP TABLE hr.dbo.riwayat_atasan;

CREATE TABLE riwayat_atasan (
	riwayatatasan_id int IDENTITY(1,1) NOT NULL,
	pegawai_id int NULL,
	atasan1 int NULL,
	atasan2 int NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	old_id int NULL,
	CONSTRAINT PK_riwayat_atasan PRIMARY KEY (riwayatatasan_id)
);


-- hr.dbo.riwayat_datadiri definition

-- Drop table

-- DROP TABLE hr.dbo.riwayat_datadiri;

CREATE TABLE riwayat_datadiri (
	riwayatdatadiri_id int IDENTITY(1,1) NOT NULL,
	nip varchar(15) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	email varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	nama varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	inisial varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	jenis_kelamin varchar(15) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	tempat_lahir varchar(250) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	tgl_lahir date NULL,
	alamat varchar(500) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	no_telp varchar(20) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	no_hp varchar(15) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	no_ktp varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	status_nikah varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	no_kk varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	gelar_depan varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	gelar_belakang varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	nidn varchar(15) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	no_serdos varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	tahun_serdos int NULL,
	file_serdos varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	tgl_masukkerja date NULL,
	file_foto varchar(500) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	file_ttd_digital varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	npwp varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	bank_pegawai varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	nama_buku varchar(150) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	no_rekening varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	bank_cabang varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	absen_pin int NULL,
	status_cuti varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	posisi_id int NULL,
	departemen_id int NULL,
	prodi_id int NULL,
	created_at datetime NULL,
	created_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	latest_riwayatapproval_id int NULL,
	bidang_ilmu varchar(350) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	jenis_perubahan varchar(25) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	before_id int NULL,
	pegawai_id int NULL,
	CONSTRAINT PK_riwayat_datadiri PRIMARY KEY (riwayatdatadiri_id)
);


-- hr.dbo.riwayat_dep_prod definition

-- Drop table

-- DROP TABLE hr.dbo.riwayat_dep_prod;

CREATE TABLE riwayat_dep_prod (
	riwayatdepprod_id int NULL,
	prodi_id int NULL,
	departemen_id int NULL,
	created_at datetime NULL,
	created_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL
);


-- hr.dbo.riwayat_diskusi definition

-- Drop table

-- DROP TABLE hr.dbo.riwayat_diskusi;

CREATE TABLE riwayat_diskusi (
	riwayatdiskusi_id int IDENTITY(1,1) NOT NULL,
	model varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	model_id int NULL,
	pengirim varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	pengirim_jabatan varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	penerima varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	penerima_inisial varchar(5) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	isi text COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	file_lampiran varchar(250) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT PK_riwayat_diskusi PRIMARY KEY (riwayatdiskusi_id)
);


-- hr.dbo.riwayat_inpassing definition

-- Drop table

-- DROP TABLE hr.dbo.riwayat_inpassing;

CREATE TABLE riwayat_inpassing (
	riwayatinpassing_id int IDENTITY(1,1) NOT NULL,
	pegawai_id int NULL,
	gol_inpassing_id int NULL,
	tmt date NULL,
	no_sk varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	tgl_sk date NULL,
	file_sk varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	before_id int NULL,
	latest_riwayatapproval_id int NULL,
	CONSTRAINT PK_r_inpassing PRIMARY KEY (riwayatinpassing_id)
);


-- hr.dbo.riwayat_jabfungsional definition

-- Drop table

-- DROP TABLE hr.dbo.riwayat_jabfungsional;

CREATE TABLE riwayat_jabfungsional (
	riwayatjabfungsional_id int IDENTITY(1,1) NOT NULL,
	pegawai_id int NULL,
	jabfungsional_id int NULL,
	tmt date NULL,
	no_sk_kopertis varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	file_sk_kopertis varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	no_sk_internal varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	file_sk_internal varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	created_at datetime DEFAULT '1' NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	latest_riwayatapproval_id int NULL,
	before_id int NULL,
	is_deleted int NULL,
	CONSTRAINT PK_riwayat_jabakademik PRIMARY KEY (riwayatjabfungsional_id)
);


-- hr.dbo.riwayat_jabstruktural definition

-- Drop table

-- DROP TABLE hr.dbo.riwayat_jabstruktural;

CREATE TABLE riwayat_jabstruktural (
	riwayatjabstruktural_id int IDENTITY(1,1) NOT NULL,
	pegawai_id int NULL,
	jabstruktural_id int NULL,
	no_sk varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	tgl_awal date NULL,
	tgl_akhir date NULL,
	pjs int NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	tgl_pengesahan date NULL,
	CONSTRAINT PK_riwayat_jabstruktural PRIMARY KEY (riwayatjabstruktural_id)
);


-- hr.dbo.riwayat_kelas definition

-- Drop table

-- DROP TABLE hr.dbo.riwayat_kelas;

CREATE TABLE riwayat_kelas (
	riwayatkelas_id int IDENTITY(1,1) NOT NULL,
	pegawai_id int NULL,
	kelas int NULL,
	jml_cuti int NULL,
	tmt date NULL,
	status_pengangkatan varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	file_pendukung varchar(150) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT PK_riwayat_kelas PRIMARY KEY (riwayatkelas_id)
);


-- hr.dbo.riwayat_pendidikan definition

-- Drop table

-- DROP TABLE hr.dbo.riwayat_pendidikan;

CREATE TABLE riwayat_pendidikan (
	riwayatpendidikan_id int IDENTITY(1,1) NOT NULL,
	pegawai_id int NULL,
	jenjang_pendidikan varchar(4) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	kode_pt varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	nama_pt varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	bidang_ilmu varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	kotaasal_pt varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	kodenegara_pt varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	file_ijazah varchar(250) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	tgl_ijazah date NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	latest_riwayatapproval_id int NULL,
	before_id int NULL,
	is_deleted int NULL,
	CONSTRAINT PK_riwayat_pendidikan PRIMARY KEY (riwayatpendidikan_id)
);


-- hr.dbo.riwayat_proddep definition

-- Drop table

-- DROP TABLE hr.dbo.riwayat_proddep;

CREATE TABLE riwayat_proddep (
	riwayathomedep_id int IDENTITY(1,1) NOT NULL,
	pegawai_id int NULL,
	homebase_id int NULL,
	departemen_id int NULL,
	date_created datetime NULL,
	CONSTRAINT PK_riwayat_homdep PRIMARY KEY (riwayathomedep_id)
);


-- hr.dbo.riwayat_stataktifitas definition

-- Drop table

-- DROP TABLE hr.dbo.riwayat_stataktifitas;

CREATE TABLE riwayat_stataktifitas (
	riwayatstataktifitas_id int IDENTITY(1,1) NOT NULL,
	pegawai_id int NULL,
	statusaktifitas_id int NULL,
	tmt date NULL,
	tgl_akhir date NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	old_id int NULL,
	CONSTRAINT PK_riwayat_stataktifitas PRIMARY KEY (riwayatstataktifitas_id)
);


-- hr.dbo.riwayat_statpegawai definition

-- Drop table

-- DROP TABLE hr.dbo.riwayat_statpegawai;

CREATE TABLE riwayat_statpegawai (
	riwayatstatpegawai_id int IDENTITY(1,1) NOT NULL,
	pegawai_id int NULL,
	statuspegawai_id int NULL,
	tmt date NULL,
	tgl_akhir date NULL,
	no_sk varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	file_sk varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	old_id int NULL,
	CONSTRAINT PK_riwayat_statpegawai PRIMARY KEY (riwayatstatpegawai_id)
);


-- hr.dbo.riwayat_status_kuliah definition

-- Drop table

-- DROP TABLE hr.dbo.riwayat_status_kuliah;

CREATE TABLE riwayat_status_kuliah (
	rstatuskuliah_id int IDENTITY(1,1) NOT NULL,
	statuskuliah_id int NULL,
	pegawai_id int NULL,
	nama_pt varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	kota_pt varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	negara_pt varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	tgl_perubahan date NULL,
	jurusan varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	status_biaya varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	semester int NULL,
	awal_kontrak date NULL,
	akhir_kontrak date NULL,
	status int NULL,
	CONSTRAINT PK_rstatus_kuliah PRIMARY KEY (rstatuskuliah_id)
);


-- hr.dbo.roles definition

-- Drop table

-- DROP TABLE hr.dbo.roles;

CREATE TABLE roles (
	id bigint IDENTITY(1,1) NOT NULL,
	team_id bigint NULL,
	name nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	guard_name nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	created_at datetime NULL,
	updated_at datetime NULL,
	CONSTRAINT PK__roles__3213E83FE2DE66C3 PRIMARY KEY (id)
);
 CREATE NONCLUSTERED INDEX roles_team_foreign_key_index ON hr.dbo.roles (  team_id ASC  )  
	 WITH (  PAD_INDEX = OFF ,FILLFACTOR = 100  ,SORT_IN_TEMPDB = OFF , IGNORE_DUP_KEY = OFF , STATISTICS_NORECOMPUTE = OFF , ONLINE = OFF , ALLOW_ROW_LOCKS = ON , ALLOW_PAGE_LOCKS = ON  )
	 ON [PRIMARY ] ;
 CREATE UNIQUE NONCLUSTERED INDEX roles_team_id_name_guard_name_unique ON hr.dbo.roles (  team_id ASC  , name ASC  , guard_name ASC  )  
	 WITH (  PAD_INDEX = OFF ,FILLFACTOR = 100  ,SORT_IN_TEMPDB = OFF , IGNORE_DUP_KEY = OFF , STATISTICS_NORECOMPUTE = OFF , ONLINE = OFF , ALLOW_ROW_LOCKS = ON , ALLOW_PAGE_LOCKS = ON  )
	 ON [PRIMARY ] ;


-- hr.dbo.status_aktifitas definition

-- Drop table

-- DROP TABLE hr.dbo.status_aktifitas;

CREATE TABLE status_aktifitas (
	statusaktifitas_id int IDENTITY(1,1) NOT NULL,
	kode_status varchar(5) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	nama_status varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	is_active int NULL,
	created_at datetime DEFAULT '1' NULL,
	created_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	old_id int NULL,
	CONSTRAINT PK_status_aktifitas PRIMARY KEY (statusaktifitas_id)
);


-- hr.dbo.status_pegawai definition

-- Drop table

-- DROP TABLE hr.dbo.status_pegawai;

CREATE TABLE status_pegawai (
	statuspegawai_id int IDENTITY(1,1) NOT NULL,
	kode_status varchar(5) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	nama_status varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	organisasi varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	is_active int NULL,
	created_at datetime DEFAULT '1' NULL,
	created_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(50) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT PK_status_pegawai PRIMARY KEY (statuspegawai_id)
);


-- hr.dbo.sysdiagrams definition

-- Drop table

-- DROP TABLE hr.dbo.sysdiagrams;

CREATE TABLE sysdiagrams (
	name sysname COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	principal_id int NOT NULL,
	diagram_id int IDENTITY(1,1) NOT NULL,
	version int NULL,
	definition varbinary(MAX) NULL,
	CONSTRAINT PK__sysdiagr__C2B05B61D0E3E1AA PRIMARY KEY (diagram_id),
	CONSTRAINT UK_principal_name UNIQUE (principal_id,name)
);


-- hr.dbo.tanggal_libur definition

-- Drop table

-- DROP TABLE hr.dbo.tanggal_libur;

CREATE TABLE tanggal_libur (
	tanggallibur_id int IDENTITY(1,1) NOT NULL,
	tahun int NULL,
	tgl_libur date NULL,
	keterangan varchar(300) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT PK_tanggal_libur PRIMARY KEY (tanggallibur_id)
);


-- hr.dbo.tanggal_tidakmasuk definition

-- Drop table

-- DROP TABLE hr.dbo.tanggal_tidakmasuk;

CREATE TABLE tanggal_tidakmasuk (
	tanggaltidakmasuk_id int IDENTITY(1,1) NOT NULL,
	jenis_ketidakhadiran varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	tgl date NULL,
	keterangan varchar(300) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	model varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	model_id int NULL,
	created_at datetime NULL,
	created_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_at datetime NULL,
	updated_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_at datetime NULL,
	deleted_by varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	cuti_id int NULL,
	izin_id int NULL,
	surattugas_id int NULL,
	pegawai_id int NULL,
	additional_info text COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT PK_tanggal_tidakmasuk PRIMARY KEY (tanggaltidakmasuk_id)
);


-- hr.dbo.[user] definition

-- Drop table

-- DROP TABLE hr.dbo.[user];

CREATE TABLE [user] (
	user_id int IDENTITY(1,1) NOT NULL,
	name nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	email nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	nip varchar(25) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	nim varchar(25) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	password varchar(100) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	is_active int NULL,
	created_at datetime NULL,
	updated_at datetime NULL,
	deleted_at datetime NULL,
	created_by nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	updated_by nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	deleted_by nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	available_roles varchar(1000) COLLATE SQL_Latin1_General_CP1_CI_AS NULL,
	CONSTRAINT PK_user PRIMARY KEY (user_id)
);


-- hr.dbo.model_has_permissions definition

-- Drop table

-- DROP TABLE hr.dbo.model_has_permissions;

CREATE TABLE model_has_permissions (
	permission_id bigint NOT NULL,
	model_type nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	model_id bigint NOT NULL,
	team_id bigint NOT NULL,
	CONSTRAINT model_has_permissions_permission_model_type_primary PRIMARY KEY (team_id,permission_id,model_id,model_type),
	CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);
 CREATE NONCLUSTERED INDEX model_has_permissions_model_id_model_type_index ON hr.dbo.model_has_permissions (  model_id ASC  , model_type ASC  )  
	 WITH (  PAD_INDEX = OFF ,FILLFACTOR = 100  ,SORT_IN_TEMPDB = OFF , IGNORE_DUP_KEY = OFF , STATISTICS_NORECOMPUTE = OFF , ONLINE = OFF , ALLOW_ROW_LOCKS = ON , ALLOW_PAGE_LOCKS = ON  )
	 ON [PRIMARY ] ;
 CREATE NONCLUSTERED INDEX model_has_permissions_team_foreign_key_index ON hr.dbo.model_has_permissions (  team_id ASC  )  
	 WITH (  PAD_INDEX = OFF ,FILLFACTOR = 100  ,SORT_IN_TEMPDB = OFF , IGNORE_DUP_KEY = OFF , STATISTICS_NORECOMPUTE = OFF , ONLINE = OFF , ALLOW_ROW_LOCKS = ON , ALLOW_PAGE_LOCKS = ON  )
	 ON [PRIMARY ] ;


-- hr.dbo.model_has_roles definition

-- Drop table

-- DROP TABLE hr.dbo.model_has_roles;

CREATE TABLE model_has_roles (
	role_id bigint NOT NULL,
	model_type nvarchar(255) COLLATE SQL_Latin1_General_CP1_CI_AS NOT NULL,
	model_id bigint NOT NULL,
	CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);
 CREATE NONCLUSTERED INDEX model_has_roles_model_id_model_type_index ON hr.dbo.model_has_roles (  model_id ASC  , model_type ASC  )  
	 WITH (  PAD_INDEX = OFF ,FILLFACTOR = 100  ,SORT_IN_TEMPDB = OFF , IGNORE_DUP_KEY = OFF , STATISTICS_NORECOMPUTE = OFF , ONLINE = OFF , ALLOW_ROW_LOCKS = ON , ALLOW_PAGE_LOCKS = ON  )
	 ON [PRIMARY ] ;


-- hr.dbo.role_has_permissions definition

-- Drop table

-- DROP TABLE hr.dbo.role_has_permissions;

CREATE TABLE role_has_permissions (
	permission_id bigint NOT NULL,
	role_id bigint NOT NULL,
	CONSTRAINT role_has_permissions_permission_id_role_id_primary PRIMARY KEY (permission_id,role_id),
	CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
	CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);


-- dbo.vw_eoffice_layanan source

-- dbo.vw_eoffice_layanan source

CREATE view vw_eoffice_layanan as
select
	layanan_id,
	no_layanan,
	pengusul_nama,
	pengusul_inisial,
	deleted_at
FROM
	eoffice.dbo.layanan
WHERE
	pengusul_inisial is not null;


-- dbo.vw_eoffice_tanggal_tidakhadir source

CREATE view  vw_eoffice_tanggal_tidakhadir as
select pengusul_nama, pengusul_email, no_layanan, jenis_ketidakhadiran, tgl, is_full_day, waktu_mulai, waktu_selesai, additional_info
from eoffice.dbo.vw_table_tanggal_tidakhadir vttt;


-- dbo.vw_referensi_jurusan source

CREATE VIEW dbo.vw_referensi_jurusan
AS
SELECT        jurusan_id, alias, jurusan AS nama_jurusan, deleted_at
FROM            referensi.dbo.jurusan;


-- dbo.vw_referensi_kelompok_jabatan source

CREATE   view vw_referensi_kelompok_jabatan as
select kjabatan_id, kjabatan, tingkatan, deleted_at
from referensi.dbo.kelompok_jabatan kj;


-- dbo.vw_referensi_prodi source

-- dbo.vw_referensi_prodi source

-- dbo.vw_referensi_prodi source

CREATE view vw_referensi_prodi as 
select p.prodi_id, p.nama_prodi, p.jenjang_pendidikan, p.alias, p.deleted_at, j.alias as jurusan_prodi_alias, j.jurusan as jurusan_prodi
from referensi.dbo.prodi as p
join referensi.dbo.jurusan j on j.jurusan_id = p.jurusan_id;


-- dbo.vw_table_departemen source

-- dbo.vw_table_departemen source

-- dbo.vw_table_departemen source

-- dbo.vw_table_departemen source

-- dbo.vw_table_departemen source

/* dbo.vw_table_departemen source
 dbo.vw_table_departemen source*/
CREATE     VIEW dbo.vw_table_departemen
AS
SELECT        d.departemen_id, d.departemen,d.abbr, d.is_active, d.alias, d.jurusan_id, d.deleted_at, d.deleted_by,
			  j.nama_jurusan as jurusan_departemen, j.alias as jurusan_departemen_alias
FROM            dbo.departemen as d
LEFT JOIN vw_referensi_jurusan as j ON j.jurusan_id = d.jurusan_id;


-- dbo.vw_table_jabatan_struktural source

create view vw_table_jabatan_struktural as select
js.jabstruktural_id, js.kode_jabatan, js.jabstruktural, js.abbr, js.alias as alias_jabstruktural,
vrkj.kjabatan,
vrp.nama_prodi as prodi_nama ,vrp.jenjang_pendidikan prodi_jenjang, vrp.alias as prodi_alias,
d.departemen as departemen_nama, d.alias as departemen_alias, js.is_active
from hr.dbo.jabatan_struktural js
left join hr.dbo.vw_referensi_prodi vrp on vrp.prodi_id = js.prodi_id
left join hr.dbo.departemen d on d.departemen_id = js.departemen_id
left join hr.dbo.vw_referensi_kelompok_jabatan vrkj on vrkj.kjabatan_id = js.kelompok_jabatan;


-- dbo.vw_table_pegawai source

CREATE   VIEW vw_table_pegawai as 
SELECT     
		(select nama,email,nip,inisial,nidn from hr.dbo.pegawai _p
			join riwayat_datadiri as _rd on _rd.riwayatdatadiri_id = _p.latest_riwayatdatadiri_id
			where _p.pegawai_id = p.atasan1
			FOR JSON PATH
		) as penyelia_1,
		(select  nama,email,nip,inisial,nidn from hr.dbo.pegawai _p
			join riwayat_datadiri as _rd on _rd.riwayatdatadiri_id = _p.latest_riwayatdatadiri_id
			where _p.pegawai_id = p.atasan2
			FOR JSON PATH
		) as penyelia_2,
		p.pegawai_id, rd.nip, rd.nama,rd.jenis_kelamin,rd.no_telp, rd.file_foto, rd.file_ttd_digital, rd.inisial, rd.email, rd.tempat_lahir, rd.tgl_lahir, rd.alamat, rd.nidn,
		rk.kelas, rk.jml_cuti, rd.gelar_depan, rd.gelar_belakang, rp.jenjang_pendidikan AS pendidikan_terakhir, 
		po.posisi_id, po.posisi, po.alias AS posisi_alias, 
		d.departemen_id, d.departemen, d.alias AS departemen_alias, d.jurusan_departemen, d.jurusan_departemen_alias,
		pr.prodi_id, pr.nama_prodi AS prodi_nama, pr.jenjang_pendidikan AS prod_jenjang, pr.alias AS prodi_alias, pr.jurusan_prodi as prodi_jurusan,pr.jurusan_prodi_alias as prodi_jurusan_alias,
		sa.nama_status AS status_aktifitas, sp.nama_status AS status_pegawai,
		sp.organisasi AS status_pegawai_organisasi, rsp.tgl_akhir AS status_pegawai_tgl_akhir, 
		jf.jabfungsional AS jabatan_fungsional, gi.nama_pangkat AS inpassing_pangkat, gi.golongan AS inpassing_golongan,
		gi.ruang AS inpassing_ruang,
         (
			select 
				js.jabstruktural,js.alias, rj2.tgl_awal, rj2.tgl_akhir, pjs, 
				kj.kjabatan_id, kj.kjabatan, kj.tingkatan,
				d.departemen_id, d.departemen,d.alias as departemen_alias, d.jurusan_departemen, d.jurusan_departemen_alias, d.abbr as departemen_abbr,
				vrp.prodi_id, vrp.nama_prodi, vrp.jurusan_prodi_alias
			FROM riwayat_jabstruktural rj2 
			JOIN jabatan_struktural js on js.jabstruktural_id = rj2.jabstruktural_id
			JOIN referensi.dbo.kelompok_jabatan kj on kj.kjabatan_id = js.kelompok_jabatan
			LEFT JOIN vw_table_departemen d ON d.departemen_id = js.departemen_id
			LEFT JOIN vw_referensi_prodi vrp ON vrp.prodi_id = js.prodi_id
			WHERE rj2.pegawai_id = p.pegawai_id and (getdate() between rj2.tgl_awal and rj2.tgl_akhir)
			FOR JSON PATH
		) as jabatan_struktural,
         p.created_at,p.created_by,p.deleted_at,p.deleted_by,p.updated_at,p.updated_by
FROM         dbo.pegawai AS p 
			 LEFT OUTER JOIN dbo.riwayat_datadiri AS rd ON rd.riwayatdatadiri_id = p.latest_riwayatdatadiri_id 
			 LEFT OUTER JOIN dbo.vw_table_departemen AS d ON d.departemen_id = rd.departemen_id 
             LEFT OUTER JOIN dbo.vw_referensi_prodi AS pr ON pr.prodi_id = rd.prodi_id 
             LEFT OUTER JOIN dbo.posisi AS po ON po.posisi_id = rd.posisi_id 
             LEFT OUTER JOIN dbo.riwayat_stataktifitas AS rsa ON rsa.riwayatstataktifitas_id = p.latest_riwayatstataktifitas_id 
             LEFT OUTER JOIN dbo.status_aktifitas AS sa ON sa.statusaktifitas_id = rsa.statusaktifitas_id 
             LEFT OUTER JOIN dbo.riwayat_statpegawai AS rsp ON rsp.riwayatstatpegawai_id = p.latest_riwayatstatpegawai_id 
             LEFT OUTER JOIN dbo.status_pegawai AS sp ON sp.statuspegawai_id = rsp.statuspegawai_id 
             LEFT OUTER JOIN dbo.riwayat_kelas AS rk ON rk.riwayatkelas_id = p.latest_riwayatkelas_id 
             LEFT OUTER JOIN dbo.riwayat_pendidikan AS rp ON rp.riwayatpendidikan_id = p.latest_riwayatpendidikan_id 
             LEFT OUTER JOIN dbo.riwayat_jabfungsional AS rj ON rj.riwayatjabfungsional_id = p.latest_riwayatjabfungsional_id 
             LEFT OUTER JOIN dbo.jabatan_fungsional AS jf ON jf.jabfungsional_id = rj.jabfungsional_id 
             LEFT OUTER JOIN dbo.riwayat_inpassing AS ri ON ri.riwayatinpassing_id = p.latest_riwayatinpassing_id 
             LEFT OUTER JOIN dbo.golongan_inpassing AS gi ON gi.gol_inpassing_id = ri.gol_inpassing_id;


-- dbo.vw_table_posisi source

create view vw_table_posisi as
select posisi_id, posisi, alias, is_active, deleted_at, deleted_by
from hr.dbo.posisi;


-- dbo.vw_table_riwayat_jabstruktural source

-- dbo.vw_table_riwayat_jabstruktural source

CREATE view [dbo].[vw_table_riwayat_jabstruktural] as
SELECT rj.riwayatjabstruktural_id, rj.pegawai_id, rj.no_sk, js.jabstruktural, rj.deleted_at,
js.abbr, js.alias, rj.tgl_pengesahan, rj.tgl_awal, rj.tgl_akhir,rd.inisial, rd.nama, 
kj.kjabatan_id, kj.kjabatan, kj.tingkatan,
d.departemen_id, d.departemen,
vrp.prodi_id, vrp.nama_prodi
FROM riwayat_jabstruktural as rj
INNER JOIN pegawai pg on pg.pegawai_id = rj.pegawai_id
INNER JOIN riwayat_datadiri rd ON rd.riwayatdatadiri_id = pg.latest_riwayatdatadiri_id
INNER JOIN jabatan_struktural js ON rj.jabstruktural_id = js.jabstruktural_id
INNER JOIN referensi.dbo.kelompok_jabatan kj ON kj.kjabatan_id = js.kelompok_jabatan
LEFT JOIN departemen d ON d.departemen_id = js.departemen_id
LEFT JOIN vw_referensi_prodi vrp ON vrp.prodi_id = js.prodi_id ;