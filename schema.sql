CREATE TABLE public.users (
    id serial NOT NULL,
    username varchar NOT NULL,
    email varchar NOT NULL,
    password varchar NOT NULL,
    CONSTRAINT users_pk PRIMARY KEY (id),
    CONSTRAINT users_username_un UNIQUE (username),
    CONSTRAINT users_email_un UNIQUE (email)
)
WITH (
	  OIDS=FALSE
) ;

CREATE TABLE public.organizations (
    id serial NOT NULL,
    founder int4 NOT NULL,
    display_name varchar NOT NULL,
    short_name varchar NOT NULL,
    CONSTRAINT organizations_pk PRIMARY KEY (id),
    CONSTRAINT organizations_un UNIQUE (short_name),
    CONSTRAINT organizations_users_fk FOREIGN KEY (founder) REFERENCES public.users(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
WITH (
    OIDS=FALSE
) ;
