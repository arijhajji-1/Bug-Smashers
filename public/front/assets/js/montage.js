const montage={processeur:0,carte_graphique:0,carte_mere:0,disque_systeme:0,boitier:0,stockage_supp:0}
window.addEventListener('load', function(event) {
    const select = document.querySelector('#montage_processeur');
    select.addEventListener('change', (e) => {


        montage.processeur=parseFloat(select.options[select.selectedIndex].dataset.prix??0);
        afficherprix();
    });
    const selectcartegraphique = document.querySelector('#montage_carte_graphique');
    selectcartegraphique.addEventListener('change', (e) => {

        montage.carte_graphique=parseFloat(selectcartegraphique.options[selectcartegraphique.selectedIndex].dataset.prix??0);
        afficherprix();
    });
    const selectcartemere = document.querySelector('#montage_carte_mere');
    selectcartemere.addEventListener('change', (e) => {

        montage.carte_mere=parseFloat(selectcartemere.options[selectcartemere.selectedIndex].dataset.prix??0);
        afficherprix();
    });
    const selectdisquesysteme = document.querySelector('#montage_disque_systeme');
    selectdisquesysteme.addEventListener('change', (e) => {

        montage.disque_systeme=parseFloat(selectdisquesysteme.options[selectdisquesysteme.selectedIndex].dataset.prix??0);
        afficherprix();
    });
    const selectboitier = document.querySelector('#montage_boitier');
    selectboitier.addEventListener('change', (e) => {

        montage.boitier=parseFloat(selectboitier.options[selectboitier.selectedIndex].dataset.prix??0);
        afficherprix();
    });
    const selectstockagesupp = document.querySelector('#montage_stockage_supp');
    selectstockagesupp.addEventListener('change', (e) => {

        montage.stockage_supp=parseFloat(selectstockagesupp.options[selectstockagesupp.selectedIndex].dataset.prix??0);
        afficherprix();
    });
});
function afficherprix(){
    const total = document.querySelector('#total');
    const montant = document.querySelector('#montage_montant');

    let prixtotal=montage.processeur+montage.carte_graphique+montage.carte_mere+montage.disque_systeme+montage.boitier+montage.stockage_supp;
total.innerHTML=prixtotal+"DT";
    montant.value=prixtotal;
}