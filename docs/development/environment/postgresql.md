# PostgreSQL

The farmOS Docker image comes pre-installed with the PostgreSQL client `psql`
command, which can be used to connect to the database and run queries from
the command line.

## Open PostgreSQL prompt

    docker exec -it farmos_www_1 psql -h db -d farm -U farm

Enter `farm` as the password.
