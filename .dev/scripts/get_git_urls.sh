awk '/url/ {print $3}' ./*/.git/config > ./git_urls.txt