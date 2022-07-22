rsync -a -v -e "ssh -p18765" --exclude=node_modules --exclude=storage/logs /Users/softblade/work/php/ganagratis/** softblad@77.104.135.77:/home/softblad/public_html/ganagratis/
# ssh softblad@77.104.135.77 -p 18765 << EOF
# cd /home/softblad/public_html/ganagratis;
# php71 artisan migrate:refresh;
# EOF