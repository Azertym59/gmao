CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "printers"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "type" varchar not null default 'standard',
  "is_default" tinyint(1) not null default '0',
  "options" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "email_verified_at" datetime,
  "password" varchar not null,
  "remember_token" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "role" varchar not null default 'technicien'
);
CREATE UNIQUE INDEX "users_email_unique" on "users"("email");
CREATE TABLE IF NOT EXISTS "password_reset_tokens"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime,
  primary key("email")
);
CREATE TABLE IF NOT EXISTS "sessions"(
  "id" varchar not null,
  "user_id" integer,
  "ip_address" varchar,
  "user_agent" text,
  "payload" text not null,
  "last_activity" integer not null,
  primary key("id")
);
CREATE INDEX "sessions_user_id_index" on "sessions"("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions"("last_activity");
CREATE TABLE IF NOT EXISTS "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE IF NOT EXISTS "job_batches"(
  "id" varchar not null,
  "name" varchar not null,
  "total_jobs" integer not null,
  "pending_jobs" integer not null,
  "failed_jobs" integer not null,
  "failed_job_ids" text not null,
  "options" text,
  "cancelled_at" integer,
  "created_at" integer not null,
  "finished_at" integer,
  primary key("id")
);
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE IF NOT EXISTS "clients"(
  "id" integer primary key autoincrement not null,
  "nom" varchar not null,
  "prenom" varchar not null,
  "societe" varchar,
  "adresse" varchar not null,
  "code_postal" varchar not null,
  "ville" varchar not null,
  "pays" varchar not null default 'France',
  "email" varchar not null,
  "telephone" varchar not null,
  "notes" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE UNIQUE INDEX "clients_email_unique" on "clients"("email");
CREATE TABLE IF NOT EXISTS "chantiers"(
  "id" integer primary key autoincrement not null,
  "client_id" integer not null,
  "nom" varchar not null,
  "description" text,
  "date_reception" date not null,
  "date_butoir" date not null,
  "etat" varchar check("etat" in('non_commence', 'en_cours', 'termine')) not null default 'non_commence',
  "reference" varchar not null,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  foreign key("client_id") references "clients"("id") on delete cascade
);
CREATE UNIQUE INDEX "chantiers_reference_unique" on "chantiers"("reference");
CREATE TABLE IF NOT EXISTS "produits"(
  "id" integer primary key autoincrement not null,
  "chantier_id" integer not null,
  "marque" varchar not null,
  "modele" varchar not null,
  "pitch" float not null,
  "utilisation" varchar check("utilisation" in('indoor', 'outdoor')) not null,
  "electronique" varchar check("electronique" in('nova', 'linsn', 'dbstar', 'brompton', 'autre')) not null,
  "electronique_detail" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("chantier_id") references "chantiers"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "dalles"(
  "id" integer primary key autoincrement not null,
  "produit_id" integer not null,
  "largeur" float not null,
  "hauteur" float not null,
  "nb_modules" integer not null,
  "alimentation" varchar not null,
  "reference_dalle" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "carte_reception" varchar,
  "hub" varchar,
  foreign key("produit_id") references "produits"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "modules"(
  "id" integer primary key autoincrement not null,
  "dalle_id" integer not null,
  "largeur" float not null,
  "hauteur" float not null,
  "nb_pixels_largeur" integer not null,
  "nb_pixels_hauteur" integer not null,
  "carte_reception" varchar,
  "hub" varchar,
  "driver" varchar,
  "shift_register" varchar,
  "buffer" varchar,
  "etat" varchar check("etat" in('non_commence', 'en_cours', 'defaillant', 'termine')) not null default 'non_commence',
  "technicien_id" integer,
  "est_occupe" tinyint(1) not null default '0',
  "reference_module" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("dalle_id") references "dalles"("id") on delete cascade,
  foreign key("technicien_id") references "users"("id")
);
CREATE TABLE IF NOT EXISTS "diagnostics"(
  "id" integer primary key autoincrement not null,
  "intervention_id" integer not null,
  "nb_leds_hs" integer not null default '0',
  "nb_ic_hs" integer not null default '0',
  "nb_masques_hs" integer not null default '0',
  "remarques" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("intervention_id") references "interventions"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "reparations"(
  "id" integer primary key autoincrement not null,
  "intervention_id" integer not null,
  "nb_leds_remplacees" integer not null default '0',
  "nb_ic_remplaces" integer not null default '0',
  "nb_masques_remplaces" integer not null default '0',
  "remarques" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("intervention_id") references "interventions"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "stock_pieces"(
  "id" integer primary key autoincrement not null,
  "categorie" varchar check("categorie" in('led', 'ic', 'masque', 'autre')) not null,
  "marque" varchar,
  "modele" varchar,
  "reference" varchar not null,
  "description" text,
  "quantite" integer not null default '0',
  "seuil_alerte" integer not null default '10',
  "prix_unitaire" numeric,
  "fournisseur" varchar,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "mouvements_stock"(
  "id" integer primary key autoincrement not null,
  "stock_piece_id" integer not null,
  "intervention_id" integer,
  "quantite" integer not null,
  "type" varchar check("type" in('entree', 'sortie')) not null,
  "commentaire" varchar,
  "user_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("stock_piece_id") references "stock_pieces"("id") on delete cascade,
  foreign key("intervention_id") references "interventions"("id"),
  foreign key("user_id") references "users"("id")
);
CREATE TABLE IF NOT EXISTS "led_datasheets"(
  "id" integer primary key autoincrement not null,
  "type" varchar not null,
  "reference" varchar not null,
  "nb_poles" integer not null,
  "disposition" varchar not null,
  "position_chanfrein" varchar check("position_chanfrein" in('haut_gauche', 'haut_droit', 'bas_gauche', 'bas_droit')) not null,
  "configuration_poles" text not null,
  "notes" text,
  "user_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id")
);
CREATE TABLE IF NOT EXISTS "produits_catalogue"(
  "id" integer primary key autoincrement not null,
  "marque" varchar not null,
  "modele" varchar not null,
  "pitch" float not null,
  "utilisation" varchar check("utilisation" in('indoor', 'outdoor')) not null,
  "electronique" varchar check("electronique" in('nova', 'linsn', 'dbstar', 'brompton', 'autre')) not null,
  "electronique_detail" varchar,
  "image_url" varchar,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "notifications"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "type" varchar not null,
  "title" varchar not null,
  "message" text not null,
  "link" varchar,
  "is_read" tinyint(1) not null default '0',
  "data" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "print_jobs"(
  "id" integer primary key autoincrement not null,
  "printer_id" integer not null,
  "content" text not null,
  "name" varchar not null,
  "status" varchar check("status" in('pending', 'printing', 'completed', 'failed')) not null default 'pending',
  "message" varchar,
  "job_token" varchar not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("printer_id") references "printers"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "telescope_entries"(
  "sequence" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "batch_id" varchar not null,
  "family_hash" varchar,
  "should_display_on_index" tinyint(1) not null default '1',
  "type" varchar not null,
  "content" text not null,
  "created_at" datetime
);
CREATE UNIQUE INDEX "telescope_entries_uuid_unique" on "telescope_entries"(
  "uuid"
);
CREATE INDEX "telescope_entries_batch_id_index" on "telescope_entries"(
  "batch_id"
);
CREATE INDEX "telescope_entries_family_hash_index" on "telescope_entries"(
  "family_hash"
);
CREATE INDEX "telescope_entries_created_at_index" on "telescope_entries"(
  "created_at"
);
CREATE INDEX "telescope_entries_type_should_display_on_index_index" on "telescope_entries"(
  "type",
  "should_display_on_index"
);
CREATE TABLE IF NOT EXISTS "telescope_entries_tags"(
  "entry_uuid" varchar not null,
  "tag" varchar not null,
  foreign key("entry_uuid") references "telescope_entries"("uuid") on delete cascade,
  primary key("entry_uuid", "tag")
);
CREATE INDEX "telescope_entries_tags_tag_index" on "telescope_entries_tags"(
  "tag"
);
CREATE TABLE IF NOT EXISTS "telescope_monitoring"(
  "tag" varchar not null,
  primary key("tag")
);
CREATE TABLE IF NOT EXISTS "interventions"(
  "id" integer primary key autoincrement not null,
  "module_id" integer not null,
  "technicien_id" integer,
  "date_debut" datetime not null,
  "date_fin" datetime,
  "date_reprise" datetime,
  "date_pause" datetime,
  "temps_total" integer not null default('0'),
  "is_completed" tinyint(1) not null default('0'),
  "created_at" datetime,
  "updated_at" datetime,
  "etat" varchar check("etat" in('En cours', 'Diagnostic terminé', 'Terminée')) not null default 'En cours',
  foreign key("module_id") references modules("id") on delete cascade on update no action,
  foreign key("technicien_id") references "users"("id")
);

INSERT INTO migrations VALUES(26,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(27,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(28,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(29,'2025_03_04_161046_create_clients_table',1);
INSERT INTO migrations VALUES(30,'2025_03_04_161607_create_chantiers_table',1);
INSERT INTO migrations VALUES(31,'2025_03_04_162520_create_produits_table',1);
INSERT INTO migrations VALUES(32,'2025_03_04_162642_create_dalles_table',1);
INSERT INTO migrations VALUES(33,'2025_03_04_162752_create_modules_table',1);
INSERT INTO migrations VALUES(34,'2025_03_04_162822_create_interventions_table',1);
INSERT INTO migrations VALUES(35,'2025_03_04_162847_create_diagnostics_table',1);
INSERT INTO migrations VALUES(36,'2025_03_04_162919_create_reparations_table',1);
INSERT INTO migrations VALUES(37,'2025_03_04_162949_create_stock_pieces_table',1);
INSERT INTO migrations VALUES(38,'2025_03_04_163030_create_mouvements_stock_table',1);
INSERT INTO migrations VALUES(39,'2025_03_04_163104_create_led_datasheets_table',1);
INSERT INTO migrations VALUES(40,'2025_03_05_055142_add_carte_reception_and_hub_to_dalles_table',1);
INSERT INTO migrations VALUES(41,'2025_03_05_055613_create_produits_catalogue_table',1);
INSERT INTO migrations VALUES(42,'2025_03_07_090741_add_role_to_users_table',1);
INSERT INTO migrations VALUES(43,'2025_03_07_155728_create_notifications_table',1);
INSERT INTO migrations VALUES(44,'2025_03_11_042401_create_printers_table',1);
INSERT INTO migrations VALUES(45,'2025_03_12_180335_create_print_jobs_table',1);
INSERT INTO migrations VALUES(46,'2025_03_13_071549_update_printers_table_for_qz_tray',1);
INSERT INTO migrations VALUES(47,'2025_03_13_080150_create_telescope_entries_table',1);
INSERT INTO migrations VALUES(48,'2025_03_13_232544_allow_null_technicien_id_interventions',1);
INSERT INTO migrations VALUES(49,'2025_03_13_232825_create_telescope_entries_table',1);
INSERT INTO migrations VALUES(50,'2025_03_14_012616_add_role_column_to_users_table',1);
INSERT INTO migrations VALUES(51,'2025_03_15_add_etat_to_interventions',2);
