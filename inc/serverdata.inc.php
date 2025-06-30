<?php
//hier werden die spielnamen definiert
$gamename[0]='DIE EWIGEN';
$gamename[1]='STOLEN EMPIRES';
$gamename[2]='ANDALUR';
$gamename[3]='ABLYON <span style="color: #3399FF;">DE</span>VOLUTION';

//hier werden alle server f�r das loginsystem definiert
$sindex=0;
$serverdata[$sindex][0]='xDE';//servertag
$serverdata[$sindex][1]='Pinwheel';//servname
$serverdata[$sindex]['container']=0;//0=de, 1=se, 2=and
$serverdata[$sindex]['containerbg']=0;
$serverdata[$sindex]['containercolor']='#3399FF';
$serverdata[$sindex][2]='10';//wt
$serverdata[$sindex][3]='60';//kt
$serverdata[$sindex][4]='beschreibungxde';//beschreibung
$serverdata[$sindex][5]='xde.die-ewigen.com';//host
$serverdata[$sindex][6]='/';//url
$serverdata[$sindex][7]=1;//paytype, 1=kostenlos
$serverdata[$sindex][8]=1;//gametyp: 1=de, 2=se
$serverdata[$sindex][9]=1;//sprache: 1=de, 2=en
$serverdata[$sindex][10]=array(0,1,2); //cooperation bei der der server aktiv ist
$serverdata[$sindex][11]=array(9999999); //bedingungen für den server: 1. feld mindestplatz in der globalen rangliste
$serverdata[$sindex][12]=0; //für neue spieler 0=nein, 1=ja
$serverdata[$sindex]['databaseKey']='xde';
$sindex++;
$serverdata[$sindex][0]='SDE';//servertag
$serverdata[$sindex][1]='Andromeda';//servname
$serverdata[$sindex]['container']=0;//0=de, 1=se, 2=and
$serverdata[$sindex]['containerbg']=0;
$serverdata[$sindex]['containercolor']='#3399FF';
$serverdata[$sindex][2]='3';//wt
$serverdata[$sindex][3]='12';//kt
$serverdata[$sindex][4]='beschreibungsde';//beschreibung
$serverdata[$sindex][5]='sde.die-ewigen.com';//host
$serverdata[$sindex][6]='/';//url
$serverdata[$sindex][7]=1;//paytype, 1=kostenlos
$serverdata[$sindex][8]=1;//gametyp: 1=de, 2=se
$serverdata[$sindex][9]=1;//sprache: 1=de, 2=en
$serverdata[$sindex][10]=array(0,1,2); //cooperation bei der der server aktiv ist
$serverdata[$sindex][11]=array(9999999); //bedingungen f�r den server: 1. feld mindestplatz in der globalen rangliste
$serverdata[$sindex][12]=1; //f�r neue spieler 0=nein, 1=ja
$serverdata[$sindex]['databaseKey']='sde';

//den testaccount nur lokal zulassen
if($GLOBALS['sv_debugmode']==1){
	//de lokal
	$sindex++;
	$serverdata[$sindex][0]='local';//servertag
	$serverdata[$sindex][1]='DE-Testserver';//servname
	$serverdata[$sindex]['container']=0;//0=de, 1=se, 2=and
	$serverdata[$sindex]['containerbg']=0;
	$serverdata[$sindex]['containercolor']='#3399FF';
	$serverdata[$sindex][2]='3';//wt
	$serverdata[$sindex][3]='12';//kt
	$serverdata[$sindex][4]='Beschreibung';//beschreibung
	$serverdata[$sindex][5]='de.test';//host
	$serverdata[$sindex][6]='/';//url
	$serverdata[$sindex][7]=0;//paytype, 0=kostenlos, aber kein credittransfer, 1=kostenlos mit credittransfer, 2=bezahlserver ohne credittransfer
	$serverdata[$sindex][8]=1;//gametyp: 1=de, 2=se
	$serverdata[$sindex][9]=1;//sprache: 1=de, 2=en
	$serverdata[$sindex][10]=array(0,1); //cooperation bei der der server aktiv ist
	$serverdata[$sindex][11]=array(9999999, 0); //bedingungen f�r den server: 1. feld mindestplatz in der globalen rangliste, 2. feld: betatester
	$serverdata[$sindex][12]=0; //f�r neue spieler 0=nein, 1=ja
	$serverdata[$sindex]['databaseKey']='de_local';
}

/*
$sindex++;
$serverdata[$sindex][0]='EDE';//servertag
$serverdata[$sindex][1]='Die Ewige Sterneninsel';//servname
$serverdata[$sindex]['container']=0;//0=de, 1=se, 2=and
$serverdata[$sindex]['containerbg']=0;
$serverdata[$sindex]['containercolor']='#3399FF';
$serverdata[$sindex][2]='3';//wt
$serverdata[$sindex][3]='12';//kt
$serverdata[$sindex][4]='beschreibungsde';//beschreibung
$serverdata[$sindex][5]='ede.bgam.es';//host
$serverdata[$sindex][6]='/';//url
$serverdata[$sindex][7]=1;//paytype, 1=kostenlos
$serverdata[$sindex][8]=1;//gametyp: 1=de, 2=se
$serverdata[$sindex][9]=1;//sprache: 1=de, 2=en
$serverdata[$sindex][10]=array(0,1,2); //cooperation bei der der server aktiv ist
$serverdata[$sindex][11]=array(9999999); //bedingungen f�r den server: 1. feld mindestplatz in der globalen rangliste
$serverdata[$sindex][12]=0; //f�r neue spieler 0=nein, 1=ja
$serverdata[$sindex]['database']='de_server_ede';
$sindex++;
$serverdata[$sindex][0]='RDE';//servertag
$serverdata[$sindex][1]='Centaurus A';//servname
$serverdata[$sindex]['container']=0;//0=de, 1=se, 2=and
$serverdata[$sindex]['containerbg']=0;
$serverdata[$sindex]['containercolor']='#3399FF';
$serverdata[$sindex][2]='1';//wt
$serverdata[$sindex][3]='4';//kt
$serverdata[$sindex][4]='Beschreibung';//beschreibung
$serverdata[$sindex][5]='rde.bgam.es';//host
$serverdata[$sindex][6]='/';//url
$serverdata[$sindex][7]=1;//paytype, 1=kostenlos
$serverdata[$sindex][8]=1;//gametyp: 1=de, 2=se
$serverdata[$sindex][9]=1;//sprache: 1=de, 2=en
$serverdata[$sindex][10]=array(0,1,2); //cooperation bei der der server aktiv ist
$serverdata[$sindex][11]=array(9999999); //bedingungen f�r den server: 1. feld mindestplatz in der globalen rangliste
$serverdata[$sindex][12]=0; //f�r neue spieler 0=nein, 1=ja
$serverdata[$sindex]['database']='de_server_rde';
$sindex++;
$serverdata[$sindex][0]='CDE';//servertag
$serverdata[$sindex][1]='Community Server';//servname
$serverdata[$sindex]['container']=0;//0=de, 1=se, 2=and
$serverdata[$sindex]['containerbg']=0;
$serverdata[$sindex]['containercolor']='#3399FF';
$serverdata[$sindex][2]='?';//wt
$serverdata[$sindex][3]='?';//kt
$serverdata[$sindex][4]='Beschreibung';//beschreibung
$serverdata[$sindex][5]='cde.bgam.es';//host
$serverdata[$sindex][6]='/';//url
$serverdata[$sindex][7]=1;//paytype, 0=kostenlos, aber kein credittransfer, 1=kostenlos mit credittransfer, 2=bezahlserver ohne credittransfer
$serverdata[$sindex][8]=1;//gametyp: 1=de, 2=se
$serverdata[$sindex][9]=1;//sprache: 1=de, 2=en
$serverdata[$sindex][10]=array(0); //cooperation bei der der server aktiv ist
$serverdata[$sindex][11]=array(9999999, 0); //bedingungen f�r den server: 1. feld mindestplatz in der globalen rangliste, 2. feld: betatester
$serverdata[$sindex][12]=0; //f�r neue spieler 0=nein, 1=ja
$serverdata[$sindex]['database']='de_server_cde';
$sindex++;
$serverdata[$sindex][0]='DDE';//servertag
$serverdata[$sindex][1]='Entwicklerserver';//servname
$serverdata[$sindex]['container']=0;//0=de, 1=se, 2=and
$serverdata[$sindex]['containerbg']=0;
$serverdata[$sindex]['containercolor']='#3399FF';
$serverdata[$sindex][2]='3';//wt
$serverdata[$sindex][3]='20 bzw. 120';//kt
$serverdata[$sindex][4]='beschreibungdde';//beschreibung
$serverdata[$sindex][5]='dde.bgam.es';//host
$serverdata[$sindex][6]='/';//url
$serverdata[$sindex][7]=0;//paytype, 0=kostenlos, aber kein credittransfer, 1=kostenlos mit credittransfer, 2=bezahlserver ohne credittransfer
$serverdata[$sindex][8]=1;//gametyp: 1=de, 2=se
$serverdata[$sindex][9]=1;//sprache: 1=de, 2=en
$serverdata[$sindex][10]=array(0,1,2); //cooperation bei der der server aktiv ist
$serverdata[$sindex][11]=array(9999999); //bedingungen f�r den server: 1. feld mindestplatz in der globalen rangliste
$serverdata[$sindex][12]=0; //f�r neue spieler 0=nein, 1=ja
$serverdata[$sindex]['database']='de_server_dde';
*/
/*
$sindex++;
$serverdata[$sindex][0]='ENSDE';//servertag
$serverdata[$sindex][1]='Endromeda';//servname
$serverdata[$sindex]['container']=0;//0=de, 1=se, 2=and
$serverdata[$sindex]['containerbg']=0;
$serverdata[$sindex]['containercolor']='#3399FF';
$serverdata[$sindex][2]='3';//wt
$serverdata[$sindex][3]='12';//kt
$serverdata[$sindex][4]='Beschreibung';//beschreibung
$serverdata[$sindex][5]='ensde.bgam.es';//host
$serverdata[$sindex][6]='/';//url
$serverdata[$sindex][7]=1;//paytype, 0=kostenlos, aber kein credittransfer, 1=kostenlos mit credittransfer, 2=bezahlserver ohne credittransfer
$serverdata[$sindex][8]=1;//gametyp: 1=de, 2=se
$serverdata[$sindex][9]=2;//sprache: 1=de, 2=en
$serverdata[$sindex][10]=array(0,1,2); //cooperation bei der der server aktiv ist
$serverdata[$sindex][11]=array(9999999); //bedingungen f�r den server: 1. feld mindestplatz in der globalen rangliste
$serverdata[$sindex][12]=1; //f�r neue spieler 0=nein, 1=ja
*/
//Die Ewigen EA1
/*
$sindex++;
$serverdata[$sindex][0]='EA1';//servertag
$serverdata[$sindex][1]='Reiner Erweiterte Arch&auml;ologie (EA)-Server';//servname
$serverdata[$sindex]['container']=0;//0=de, 1=se, 2=and
$serverdata[$sindex]['containerbg']=0;
$serverdata[$sindex]['containercolor']='#3399FF';
$serverdata[$sindex][2]='';//wt
$serverdata[$sindex][3]='';//kt
$serverdata[$sindex][4]='Beschreibung';//beschreibung
$serverdata[$sindex][5]='abl1.bgam.es';//host
$serverdata[$sindex][6]='/';//url
$serverdata[$sindex][7]=1;//paytype, 0=kostenlos, aber kein credittransfer, 1=kostenlos mit credittransfer, 2=bezahlserver ohne credittransfer
$serverdata[$sindex][8]=4;//gametyp: 1=de, 2=se, 3=alu, 4=abl, 5=and
$serverdata[$sindex][9]=1;//sprache: 1=de, 2=en
$serverdata[$sindex][10]=array(0,5); //cooperation bei der der server aktiv ist
$serverdata[$sindex][11]=array(9999999); //bedingungen f�r den server: 1. feld mindestplatz in der globalen rangliste
$serverdata[$sindex][12]=0; //f�r neue spieler 0=nein, 1=ja
$serverdata[$sindex]['database']='abl_server_abl1';
*/
//Die Ewigen EFTA1
/*
$sindex++;
$serverdata[$sindex][0]='EFTA1';//servertag
$serverdata[$sindex][1]='Die Ewigen - Reiner EFTA-Server';//servname
$serverdata[$sindex]['container']=0;//0=de, 1=se, 2=and, 3=abl
$serverdata[$sindex]['containerbg']=0;
$serverdata[$sindex]['containercolor']='#3399FF';
$serverdata[$sindex][2]='';//wt
$serverdata[$sindex][3]='';//kt
$serverdata[$sindex][4]='Beschreibung';//beschreibung
$serverdata[$sindex][5]='alu1.bgam.es';//host
$serverdata[$sindex][6]='/';//url
$serverdata[$sindex][7]=1;//paytype, 0=kostenlos, aber kein credittransfer, 1=kostenlos mit credittransfer, 2=bezahlserver ohne credittransfer
$serverdata[$sindex][8]=3;//gametyp: 1=de, 2=se, 3=alu
$serverdata[$sindex][9]=1;//sprache: 1=de, 2=en
$serverdata[$sindex][10]=array(0,4); //cooperation bei der der server aktiv ist
$serverdata[$sindex][11]=array(9999999); //bedingungen f�r den server: 1. feld mindestplatz in der globalen rangliste
$serverdata[$sindex][12]=0; //f�r neue spieler 0=nein, 1=ja
 */
//de30
/*
$sindex++;
$serverdata[$sindex][0]='DE 3.0';//servertag
$serverdata[$sindex][1]='Ewige Insel';//servname
$serverdata[$sindex]['container']=0;//0=de, 1=se, 2=and
$serverdata[$sindex]['containerbg']=0;
$serverdata[$sindex]['containercolor']='#3399FF';
$serverdata[$sindex][2]='3';//wt
$serverdata[$sindex][3]='12';//kt
$serverdata[$sindex][4]='beschreibungsde';//beschreibung
$serverdata[$sindex][5]='de30.bgam.es';//host
$serverdata[$sindex][6]='/';//url
$serverdata[$sindex][7]=0;//paytype, 0=kostenlos, aber kein credittransfer, 1=kostenlos mit credittransfer, 2=bezahlserver ohne credittransfer
$serverdata[$sindex][8]=1;//gametyp: 1=de, 2=se
$serverdata[$sindex][9]=1;//sprache: 1=de, 2=en
$serverdata[$sindex][10]=array(0,1,2); //cooperation bei der der server aktiv ist
$serverdata[$sindex][11]=array(9999999, 0); //bedingungen f�r den server: 1. feld mindestplatz in der globalen rangliste, 2. feld: betatester
$serverdata[$sindex][12]=0; //f�r neue spieler 0=nein, 1=ja
*/
/*
$sindex++;
$serverdata[$sindex][0]='ADE';//servertag
$serverdata[$sindex][1]='ADE';//servname
$serverdata[$sindex]['container']=3;//0=de, 1=se, 2=and, 3=abl
$serverdata[$sindex]['containerbg']=0;
$serverdata[$sindex]['containercolor']='#b526f8';
$serverdata[$sindex][2]='';//wt
$serverdata[$sindex][3]='';//kt
$serverdata[$sindex][4]='Beschreibung';//beschreibung
$serverdata[$sindex][5]='ade.bgam.es';//host
$serverdata[$sindex][6]='/';//url
$serverdata[$sindex][7]=1;//paytype, 0=kostenlos, aber kein credittransfer, 1=kostenlos mit credittransfer, 2=bezahlserver ohne credittransfer
$serverdata[$sindex][8]=4;//gametyp: 1=de, 2=se
$serverdata[$sindex][9]=1;//sprache: 1=de, 2=en
$serverdata[$sindex][10]=array(0,1); //cooperation bei der der server aktiv ist
$serverdata[$sindex][11]=array(9999999); //bedingungen f�r den server: 1. feld mindestplatz in der globalen rangliste
*/

//Ablyon 2 - Server local
/*
if($sv_debugmode==1){
	$sindex++;
	$serverdata[$sindex][0]='local';//servertag
	$serverdata[$sindex][1]='ADE-Lokal';//servname
	$serverdata[$sindex]['container']=3;//0=de, 1=se, 2=and, 3=abl
	$serverdata[$sindex]['containerbg']=0;
	$serverdata[$sindex]['containercolor']='#b526f8';
	$serverdata[$sindex][2]='';//wt
	$serverdata[$sindex][3]='';//kt
	$serverdata[$sindex][4]='Beschreibung';//beschreibung
	$serverdata[$sindex][5]='127.0.0.1';//host
	$serverdata[$sindex][6]='/ablyon2/source/';//url
	$serverdata[$sindex][7]=1;//paytype, 0=kostenlos, aber kein credittransfer, 1=kostenlos mit credittransfer, 2=bezahlserver ohne credittransfer
	$serverdata[$sindex][8]=4;//gametyp: 1=de, 2=se
	$serverdata[$sindex][9]=1;//sprache: 1=de, 2=en
	$serverdata[$sindex][10]=array(0,1); //cooperation bei der der server aktiv ist
	$serverdata[$sindex][11]=array(9999999); //bedingungen f�r den server: 1. feld mindestplatz in der globalen rangliste
}
*/

//Andalur 1
/*
$sindex++;
$serverdata[$sindex][0]='AND1';//servertag
$serverdata[$sindex][1]='Andalur Welt 1';//servname
$serverdata[$sindex]['container']=2;//0=de, 1=se, 2=and
$serverdata[$sindex]['containerbg']=1;
$serverdata[$sindex]['containercolor']='#f88426';
$serverdata[$sindex][2]='';//wt
$serverdata[$sindex][3]='';//kt
$serverdata[$sindex][4]='Beschreibung';//beschreibung
$serverdata[$sindex][5]='and1.bgam.es';//host
$serverdata[$sindex][6]='/';//url
$serverdata[$sindex][7]=1;//paytype, 0=kostenlos, aber kein credittransfer, 1=kostenlos mit credittransfer, 2=bezahlserver ohne credittransfer
$serverdata[$sindex][8]=5;//gametyp: 1=de, 2=se, 3=alu, 4=abl, 5=and
$serverdata[$sindex][9]=1;//sprache: 1=de, 2=en
$serverdata[$sindex][10]=array(0,6,7); //cooperation bei der der server aktiv ist
$serverdata[$sindex][11]=array(9999999, 0); //bedingungen f�r den server: 1. feld mindestplatz in der globalen rangliste, 2. feld: betatester
$serverdata[$sindex][12]=0; //f�r neue spieler 0=nein, 1=ja
$serverdata[$sindex]['database']='and_server_and1';
*/
//Andalur lokal
/*
if($sv_debugmode==1){
	$sindex++;
	$serverdata[$sindex][0]='local';//servertag
	$serverdata[$sindex][1]='AND-Testserver';//servname
	$serverdata[$sindex]['container']=2;//0=de, 1=se, 2=and
	$serverdata[$sindex]['containerbg']=1;
	$serverdata[$sindex]['containercolor']='#f88426';
	$serverdata[$sindex][2]='';//wt
	$serverdata[$sindex][3]='';//kt
	$serverdata[$sindex][4]='Beschreibung';//beschreibung
	$serverdata[$sindex][5]='127.0.0.1';//host
	$serverdata[$sindex][6]='/andalur/game/';//url
	$serverdata[$sindex][7]=1;//paytype, 0=kostenlos, aber kein credittransfer, 1=kostenlos mit credittransfer, 2=bezahlserver ohne credittransfer
	$serverdata[$sindex][8]=5;//gametyp: 1=de, 2=se, 3=alu, 4=abl, 5=and
	$serverdata[$sindex][9]=1;//sprache: 1=de, 2=en
	$serverdata[$sindex][10]=array(0); //cooperation bei der der server aktiv ist
	$serverdata[$sindex][11]=array(9999999); //bedingungen f�r den server: 1. feld mindestplatz in der globalen rangliste
	$serverdata[$sindex][12]=0; //f�r neue spieler 0=nein, 1=ja
}
*/
/*
//stolen empires
$sindex++;
$serverdata[$sindex][0]='NSE';//servertag
$serverdata[$sindex][1]='Seltar';//servname
$serverdata[$sindex]['container']=1;//0=de, 1=se, 2=and, 3=abl
$serverdata[$sindex]['containerbg']=2;
$serverdata[$sindex]['containercolor']='#931b1b';
$serverdata[$sindex][2]='6';//wt
$serverdata[$sindex][3]='6';//kt
$serverdata[$sindex][4]='Beschreibung';//beschreibung
$serverdata[$sindex][5]='nse.bgam.es';//host
$serverdata[$sindex][6]='/';//url
$serverdata[$sindex][7]=1;//paytype, 0=kostenlos, aber kein credittransfer, 1=kostenlos mit credittransfer, 2=bezahlserver ohne credittransfer
$serverdata[$sindex][8]=2;//gametyp: 1=de, 2=se
$serverdata[$sindex][9]=1;//sprache: 1=de, 2=en
$serverdata[$sindex][10]=array(0,3); //cooperation bei der der server aktiv ist
$serverdata[$sindex][11]=array(9999999); //bedingungen f�r den server: 1. feld mindestplatz in der globalen rangliste
$serverdata[$sindex][12]=0; //f�r neue spieler 0=nein, 1=ja
$sindex++;
$serverdata[$sindex][0]='SSE';//servertag
$serverdata[$sindex][1]='Kulaz';//servname
$serverdata[$sindex]['container']=1;//0=de, 1=se, 2=and, 3=abl
$serverdata[$sindex]['containerbg']=2;
$serverdata[$sindex]['containercolor']='#931b1b';
$serverdata[$sindex][2]='3';//wt
$serverdata[$sindex][3]='3';//kt
$serverdata[$sindex][4]='Beschreibung';//beschreibung
$serverdata[$sindex][5]='sse.bgam.es';//host
$serverdata[$sindex][6]='/';//url
$serverdata[$sindex][7]=1;//paytype, 0=kostenlos, aber kein credittransfer, 1=kostenlos mit credittransfer, 2=bezahlserver ohne credittransfer
$serverdata[$sindex][8]=2;//gametyp: 1=de, 2=se
$serverdata[$sindex][9]=1;//sprache: 1=de, 2=en
$serverdata[$sindex][10]=array(0,3); //cooperation bei der der server aktiv ist
$serverdata[$sindex][11]=array(9999999); //bedingungen f�r den server: 1. feld mindestplatz in der globalen rangliste
$serverdata[$sindex][12]=1; //f�r neue spieler 0=nein, 1=ja
//den testaccount nur lokal zulassen
if($sv_debugmode==1){
//se lokal
$sindex++;
$serverdata[$sindex][0]='local';//servertag
$serverdata[$sindex][1]='SE-Testserver';//servname
$serverdata[$sindex]['container']=1;//0=de, 1=se, 2=and, 3=abl
$serverdata[$sindex]['containerbg']=2;
$serverdata[$sindex]['containercolor']='#931b1b';
$serverdata[$sindex][2]='3';//wt
$serverdata[$sindex][3]='3';//kt
$serverdata[$sindex][4]='Beschreibung';//beschreibung
$serverdata[$sindex][5]='127.0.0.1';//host
$serverdata[$sindex][6]='/se/nng2/';//url
$serverdata[$sindex][7]=0;//paytype, 0=kostenlos, aber kein credittransfer, 1=kostenlos mit credittransfer, 2=bezahlserver ohne credittransfer
$serverdata[$sindex][8]=2;//gametyp: 1=de, 2=se
$serverdata[$sindex][9]=1;//sprache: 1=de, 2=en
$serverdata[$sindex][10]=array(0,1); //cooperation bei der der server aktiv ist
$serverdata[$sindex][11]=array(9999999); //bedingungen f�r den server: 1. feld mindestplatz in der globalen rangliste
}

 */
?>
