# VAF.PureServiceStatistikk

Webside som inneholder statistikk over informasjon i PureService.


## Oppsett av utviklings miljø
  1. Installer wamp64
  2. Gå inn på https://github.com/Microsoft/msphpsql/releases
  3. Last ned Windows 7.2.zip
  4. Legg php_sqlsrv_71_ts_x64.dll og php_pdo_sqlsrv_71_ts_x64.dll til i ext mappen til wamp64
  5. Bytt fra php 5 til php 7.1
  6. Legg til extension=php_sqlsrv_71_ts_x64.dll og extension=php_pdo_sqlsrv_71_ts_x64.dll i php.ini filen, skal ligger under Dynamic Extensions (linje 862) (ikke bruk non-thread safe (nts))  


## Funksjonalitet

## Statistikk 1
-	Nye saker/åpne saker/lukket sakker
-	Se på hver avdeling (KKG, KVA, TAN)
-	Velge periode

## Statistikk 2
-	Antall saker per. Kategori
-	Per. Avdeling
-	Per. Periode (dag, måned, år)

## Statistikk 3
-	Løsning tid (brukt på sak)
-	Gjen. Løsnings tid
-	Periode
-	Avdeling

## PDF
- Lager rapport (.pdf) for hver uke, evt. måned og år


## Videregående skoler
1. Vågsbygd videregående skole
2. Vennesla videregående skole
3. Kvadraturen skolesenter
4. Tangen videregående skole
5. Kristiansand Katedralskole Gimle
6. Søgne videregående skole
7. Mandal videregående skole
8. Byremo videregående skole
9. Eilert Sundt videregående skole
10. Flekkefjord videregående skole
11. Sirdal videregående skole
<<<<<<< HEAD
=======


## TODO
-  [] Legg variabler som er brukt ofte (variabler som er brukt Global) i en egen fil
-  [] Legg til sikkerhet (CSRF, XSS, HTTP Only cookies osv.)


>>>>>>> dad46c9192c237b4414ae8400774b54247230897
