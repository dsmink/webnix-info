#!/bin/bash

# Execute.
ping -c 1 -i3 $1 &> /dev/null

# Get result code.
if [ $? -eq 0 ]
then
  echo "$1;$2;UP" >> $3
else
  echo "$1;$2;DOWN" >> $3
fi
