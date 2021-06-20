#
# Add SQL definition of database tables
#
CREATE TABLE tx_willsideas_domain_model_idea (
   tstamp int(11) DEFAULT 0 NOT NULL,
   crdate int(11) DEFAULT 0 NOT NULL,
   title varchar(255) DEFAULT '' NOT NULL,
   description text NOT NULL,
   category int(11) DEFAULT 0 NOT NULL,
   status int(11) DEFAULT 0 NOT NULL,
   user int(11) DEFAULT 0 NOT NULL,
   image varchar(255) DEFAULT 0,
   voted_users int(11) DEFAULT 0 NOT NULL
);
CREATE TABLE tx_willsideas_domain_model_idea_user_mm (
	uid_local int(11) unsigned DEFAULT 0 NOT NULL,
	uid_foreign int(11) unsigned DEFAULT 0 NOT NULL,
	sorting int(11) unsigned DEFAULT 0 NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT 0 NOT NULL,
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);