#!/bin/bash

app/console doctrine:schema:drop  --force --full-database && \
app/console doctrine:database:drop --force &&

app/console doctrine:database:create &&
app/console doctrine:schema:create
