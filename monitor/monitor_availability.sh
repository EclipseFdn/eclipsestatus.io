#!/bin/bash
#*********************************************************************
# * Copyright (c) 2005-2020 Eclipse Foundation, Inc.
# *
# * This program and the accompanying materials are made
# * available under the terms of the Eclipse Public License 2.0
# * which is available at https://www.eclipse.org/legal/epl-2.0/
# *
# * SPDX-License-Identifier: EPL-2.0
# **********************************************************************/

OUTFILE_PREFIX=~/monitor/monitor_availability_

# Given a list of URIs in file connectlist (one per line)
# fetch the http status code for each, and put the last 10 entries in $OUTFILE_PREFIX
# Run this as a cronjob every 3 minutes and it gives the overall service availability over
# the last 20 minutes

for i in $(cat connectlist); do
  HOSTNAME=$(echo $i | egrep -o "[a-z0-9]+\.[a-zA-Z0-9]+.org")
  OUTFILE=${OUTFILE_PREFIX}$HOSTNAME.out
  OUTFILE_TMP=${OUTFILE}.tmp

  # trim the outfile, keep 9 entries then add the latest to the top
  head -n 9 $OUTFILE > $OUTFILE_TMP

  curl -s -o /dev/null -w "%{http_code}"  --header "Accept-encoding:gzip,deflate" $i > $OUTFILE
  echo >> $OUTFILE
  cat $OUTFILE_TMP >> $OUTFILE
  rm -f $OUTFILE_TMP
  cp $OUTFILE /localsite/out
done

