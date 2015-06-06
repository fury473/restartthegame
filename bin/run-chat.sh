#!/bin/bash
pid_file='/home/restartthegame/symfony/bin/chat.pid'
console_file='/home/restartthegame/symfony/app/console'
log_file='/home/restartthegame/symfony/app/logs/chat.log'
if [ -e $pid_file ]; then
    while read pid; do
        kill $pid
    done < $pid_file
    rm $pid_file
fi
php $console_file app:ioserver:run --env=prod --no-debug 2>>$log_file &
echo $! >> $pid_file
exit 1
