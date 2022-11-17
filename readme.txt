Slovenska Pošta - Doprava (V4.0) pre OpenCart 2.3.0.2
==========================================

Modul vyráta cenu prepravy na základe hmotnosti tovaru a obalu, prípadne poistné 
podľa hodnoty košíka. Všetky doplnkové služby ako dobierka, poistenie, krehké atď 
sa aktivujú/deaktivujú v administrácii. Modul slúži pre určenie ceny 
prepravy v rámci SR a ČR, Európskych krajín (EÚ + dalšie, spolu cca 48 krajín) 
a ostatných krajín (172). Tarify prepravného nie sú sťahované z webu slovenskej 
pošty ale sú priamo uložené na Vašom servery s možnosťou úpravy. Východzie 
nastavenie je s aktuálnymi cenami (*01/18). Pre správy výpočet hmotnosti 
je k dispozícii pole pre váhu Vášho obalového materiálu. V ponuke je tiež možnosť 
dopravy zadarmo podľa voliteľne nastavenej ceny. Doplkové služby ako neskladné, 
krehké, doručenka, do vlastných rúk, poistenie, dobierka sa v rámci EÚ a sveta 
zarátajú len v prípade, ak si tieto služby povolíte, a najmä ak to SP pre danú 
krajinu umožňuje. 




Inštalácia
================================================================================

Inštalačný balíček obsahuje: 
1) modul dopravy
2) platobný modul
3) modul celková objednávka

Pre použitie tohto rozšírenia postupujte podľa nasledujúcich krokov

1a)V admin rozhraní vstúpte do Extensions > Extension Installer a nahrajte stiahnutý archív slovenskej pošty.
    V prípade problémov s nastavením ftp, postupujte alternatínym spôsobom nahratia archívu podľa bodu 1b)
1b) Rozzipujte stiahnutý archív a obsah súboru upload skopírujte do rootu Vášho webu.

2) Vstúpte do System > Users > User Group > Edit Administrator
3) Nastavte povolenia pre prístup a úpravu pre 'shipping/slovenska_posta' a 'payment/slovenska_posta_cod'
4) Vstúpte do Extensions > Extensions > Shipping. Inštalujte modul 'Slovenská pošta'. Následne ho upravte podľa potreby a uložte.
5) Vstúpte do Extension > Extensions > Payments. Inštalujte modul 'Slovenská Pošta Dobierka'. Následne ho upravte podľa potreby a uložte.
6) Vstúpte do Extension > Extensions > Order Totals. Inštalujte modul 'Dobierka Slovenská Pošta'. Následne ho upravte podľa potreby a uložte.
To je všetko! Rozšírenie máte nainštalované.


Poznámka
================================================================================
Pre aktiváciu platby Dobierka Slovenskej pošty je nutné aktivovať platobný modul 
spolu s modulom celková objednávka. Platba Dobierka Slovenskej pošty sa bude 
zobrazovať len v prípade, ak:
1) zvolená doprava je z ponuky dopravy Slovenskej pošty, 
2) služba dobierka je pre danú službu dostupná
3) služba dobierka je v module dopravy pre danú službu aktivovaná.
4) v moduloch platieb je aktivovaný modul Dobierka Slovenská Pošta
5) v moduloch celková objednávka je aktivovaný modul Dobierka Slovenská Pošta

Modul dopravy sa môže používať aj bez modulu platby. V prípade deaktivácie 
modulu celková objednávka / dobierka slovenská pošta sa cena dobierky v prípade 
jej použitia bude zarátavať do ceny prepravy.




Ďalšia pomoc a prispôsobené verzie
====================================

Tento modul bol úspešne testovaný pre verziu OpenCart 2.3.0.2
Nepoužívajte iné verzie OpenCartu s týmto modulom.
