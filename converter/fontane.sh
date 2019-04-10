#!/bin/bash
# dokuwiki to markdown
# specified for Fontane Notizbuch-Projekt
# see fontane-notizbücher.de

# set up your wiki
WIKI=""
USER=""
PWD=""

CRED="u=$USER&p=$PWD"

# clean up
echo "clean up the working directories"
rm -rf input/
rm -rf output/
mkdir input
mkdir output

# and go ahead
echo "loading files from dokuWiki"
cd input

#Startseite
	wget -q "$WIKI?id=fontane:startseite&do=export_raw&$CRED" &&
#Einführung (vorher Überblickskommentar zu allen Notizbüchern, noch nicht freigegeben)
#Inhaltsverzeichnis
#Über das Projekt
	wget -q "$WIKI?id=fontane:ueber_das_projekt&do=export_raw&$CRED" &&
#Editionsteam
	wget -q "$WIKI?id=fontane:editionsteam&do=export_raw&$CRED" &&
#Institutionen
	wget -q "$WIKI?id=fontane:institutionen&do=export_raw&$CRED" &&
#Technische Dokumentation
#Nutzungshinweise
	wget -q "$WIKI?id=fontane:nutzungshinweise&do=export_raw&$CRED" &&
#Zitationshinweise
	wget -q "$WIKI?id=fontane:zitationshinweise&do=export_raw&$CRED" &&
#Impressum
	wget -q "$WIKI?id=fontane:impressum&do=export_raw&$CRED" &&
#Testdatei
	wget -q "$WIKI?id=fontane:testdatei&do=export_raw&$CRED" &&
# ^^^ add more wgets as you like ^^^

#preprocessing
echo "rename and remove unsupported syntax"
rename "s/doku.php\?id=fontane:(.*)\&do\=export_raw\&u\=$USER\&p=$PWD/\1.txt/" doku.php*

# replace inline pseudo code
#grep -l "//<" * | xargs sed -i "s|//<|//\&lt;|g"
cd ..

#convert
echo "convert..."
php convert.php input/ output/

# postprocessing
cd output

grep -l "* Transkription:" * | xargs sed -i "s|//>//|*>*|g"
grep -l "\\\\" * | xargs sed -i "s/\\\\/\n\n/g"
grep -l "<code xml>" * | xargs sed -i "s|<code xml>|\n\`\`\`xml\n|g"
grep -l "\s$" * | xargs sed -i "s/\s$//"
grep -l "</code>" * | xargs sed -i "s|<\/code>|\`\`\`\n|g"
grep -l "\n```" * | xargs sed -i "s|(.[^\n])\n```|\1\n\n```|g"
cd ..
echo "
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
done. view your markdown files in output/

...but wait!"
sleep 2
echo "We have to get the images as well!"
cd input
grep -ho "{{.*}}" * 
# get the access token
cd input
FILE=$(grep -l "{{.*}}" * | head -n 1 | sed "s/.txt//g")
TOK=$(wget -q "$WIKI?id=fontane:$FILE&do=export_xhtml&$CRED" | grep -ho "tok=.*?&")
cd ..
touch $TOK

