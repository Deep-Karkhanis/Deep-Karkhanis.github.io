#!/bin/bash

# /usr/local/bin/face_recognition $1 $2 | egrep "11$"| cut -d ',' -f1 > out1
# python3 compress.py $3
python3 compress.py $1 $2 $3