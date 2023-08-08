let numex=document.getElementById("numeroexp") as HTMLInputElement
let nomexpediteur=document.getElementById("nomexpediteur") as HTMLInputElement
let operateur=document.getElementById("operateur") as HTMLInputElement
let button=document.getElementById("btn") as HTMLInputElement
let typeop=document.getElementById("type") as HTMLInputElement
let montantt=document.getElementById("montant") as HTMLInputElement
let numdesti=document.getElementById("numeroReceveur") as HTMLInputElement
let bunt=document.getElementById("btnd") as HTMLInputElement
let comptes=document.getElementById("comptesTable") as HTMLInputElement
let boutonC=document.getElementById("btnCreer") as HTMLInputElement
let numC=document.getElementById("numero") as HTMLInputElement
let fourC=document.getElementById("fournisseurSelect") as HTMLInputElement
let soldeC=document.getElementById("solde") as HTMLInputElement
let numCl=document.getElementById("numeroc") as HTMLInputElement
let nomC=document.getElementById("nomc") as HTMLInputElement
let bntC=document.getElementById("ajt") as HTMLInputElement





const port='http://127.0.0.1:8000/api';
 
const fournisseur:string[]=['choisir un operateur','OM','WV','WR','CB'];
const type:string[]=['choisir un type','depot','retrait','transfert'];

function isnum($numero:string):boolean{
    let $num=$numero.replace(' ','');
    if($num.length!=9){
        return false
    }
    if(isNaN(+$num)){
        return false
    }
    return true
  }



  function iscompte($compte:string):boolean{
    let supeSpace=$compte.trim()//retier les espace au debut et a la fin de la chaione//
     let expo=supeSpace.split('_');
     if(expo.length!=2 ||isnum(expo[0]) ||Object.values(fournisseur).includes(expo[0])){
        return false
     }

     return true
}
function chargerSe(selecte:HTMLInputElement,tab:string[]){
    tab.forEach((element=>{
        let option =document.createElement("option");
        option.innerHTML=element
        selecte.appendChild(option);
    }));
}
chargerSe(operateur,fournisseur);
chargerSe(typeop,type);
chargerSe(fourC,fournisseur)

  numex.addEventListener("change",()=>{
    if(!(iscompte(numex.value) || isnum(numex.value))){
  nomexpediteur.value="ce numero existe pas";
}
 nomexpediteur.value="";
 fetch(port+"/compte/"+numex.value)

 .then(response=>response.json())
 .then(monresponse=>{
 if(monresponse.data){

    nomexpediteur.value=monresponse.data.nom
    console.log(monresponse.data.nom);
 }else{
    alert(monresponse.message);
    
 }
   });
  });




  //ajouter client
  bntC.addEventListener('click',()=>{
   
    event?.preventDefault();
const client={
  nom:nomC.value,
  numero:numC.value,
  
}

const requette=JSON.stringify(client);
fetch('http://127.0.0.1:8000/api/ajout', {
  method: 'POST', // ou 'GET' si c'est une requête GET
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: requette,
})
  .then(response => response.json())
  .then(data => {
    // Faites quelque chose avec la réponse de l'API
    console.log(data);
  })
  .catch(error => {
    // Gérez les erreurs
    console.error('Erreur lors de la requête:', error);
  });


  });


  //fin des validations//
  //debut du front depot method post//////////////////


  boutonC.addEventListener('click', () => {
    event?.preventDefault();
    const compte = {
      numero: numC.value,
      opperateur: fourC.value,
      solde: soldeC.value
    };
    const requestBody = JSON.stringify(compte);
    
    // Créer le compte
    fetch('http://127.0.0.1:8000/api/crecompte', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: requestBody,
    })
    .then(response => response.json())
    .then(data => {
      console.log(data); // Affiche la réponse de la création du compte
      
      // Récupérer la liste des comptes après la création réussie
      fetch('http://127.0.0.1:8000/api/compte')
        .then(response => response.json())
        .then(data => {
          comptes.innerHTML = ''; // Efface le contenu précédent de la table
          data.forEach(compte => {
            // Créez une nouvelle ligne pour chaque compte
            const row = document.createElement('tr');
            row.innerHTML = `
              <td>${compte.id}</td>
              <td>${compte.etat}</td>
              <td>${compte.client_id}</td>
              <td>${compte.opperateur}</td>
              <td>${compte.solde}</td>
              <td>
                <button type="submit" class="bloc">Fermer</button>
              </td>
              <td>
                <button type="submit" class="bloc">Bloquer</button>
              </td>`
            comptes.appendChild(row); // Ajoutez la nouvelle ligne à la table
          });
        });
    });
  });
  

  //--------
     button.addEventListener("click", () => {
      event?.preventDefault();
      const requestData = {
        numero: numex.value,
        type: typeop.value,
        opperateur: operateur.value,
        montant: montantt.value,
      };
  
      // Utilisez la méthode JSON.stringify pour convertir l'objet en chaîne JSON
      const requestBody = JSON.stringify(requestData);
  
      // Ensuite, utilisez la méthode fetch pour envoyer une requête POST (ou GET) avec les données
      fetch('http://127.0.0.1:8000/api/depot', {
        method: 'POST', // ou 'GET' si c'est une requête GET
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: requestBody,
      })
        .then(response => response.json())
        .then(data => {
          // Faites quelque chose avec la réponse de l'API
          console.log(data);
        })
        .catch(error => {
          // Gérez les erreurs
          console.error('Erreur lors de la requête:', error);
        });

        button.addEventListener('click', () =>{
          alert('fff')
          event?.preventDefault();
        const montransfert = {
          numexpr: numex.value,
          numerdest:numdesti.value,
          type: typeop.value,
          opperateur: operateur.value,
          montant: montantt.value,
        };
        const chaine=JSON.stringify(montransfert);
        fetch('http://127.0.0.1:8000/api/transfert', {
          method: 'POST', // ou 'GET' si c'est une requête GET
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: chaine,
        })
        .then(response => response.json())
        .then(data => {
          // Faites quelque chose avec la réponse de l'API
          console.log(data);
        })
        .catch(error => {
          // Gérez les erreurs
          console.error('Erreur lors de la requête:', error);
        });


     });
    });

     bunt.addEventListener('click',()=>{
      event?.preventDefault(); 
      const updatedState = 2
      const  donneAModif={
        etat:updatedState
      }
      fetch('http://127.0.0.1:8000/api/annuler',{

      method: 'PUT', // ou 'GET' si c'est une requête GET
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body:JSON.stringify(donneAModif) ,
      })
      .then(response => response.json())
      .then(data => {
        // Faites quelque chose avec la réponse de l'API
        console.log(data);
      })
      .catch(error => {
        // Gérez les erreurs
        console.error('Erreur lors de la requête:', error);
      });



     })
     
  //     for (const iterator of blocomptes) {
  //     iterator.addEventListener('click',()=>{
  //     event?.preventDefault(); 
  //     const updatedState = 2
  //     const  donneAModif={
  //       etat:updatedState
  //     }
  //     fetch('http://127.0.0.1:8000/api/bloque/1',{

  //     method: 'PUT', // ou 'GET' si c'est une requête GET
  //     headers: {
  //       'Content-Type': 'application/json',
  //       'Accept': 'application/json'
  //     },
  //     body:JSON.stringify(donneAModif) ,
  //     })
  //     .then(response => response.json())
  //     .then(data => {
  //       // Faites quelque chose avec la réponse de l'API
  //       console.log(data);
  //     })
  //     .catch(error => {
  //       // Gérez les erreurs
  //       console.error('Erreur lors de la requête:', error);
  //     });
  

  //   })
  // }


  
     
  // for (const iterator of blocomptes) {
  //   iterator.addEventListener('click',(e)=>{
  //   event?.preventDefault(); 
  //   const updatedState = 1
  //   const  donneAModif={
  //     etat:updatedState
  //   }
  //   fetch('http://127.0.0.1:8000/api/debloque/1',{

  //   method: 'PUT', // ou 'GET' si c'est une requête GET
  //   headers: {
  //     'Content-Type': 'application/json',
  //     'Accept': 'application/json'
  //   },
  //   body:JSON.stringify(donneAModif) ,
  //   })
  //   .then(response => response.json())
  //   .then(data => {
  //     // Faites quelque chose avec la réponse de l'API
  //     console.log(data);
  //   })
  //   .catch(error => {
  //     // Gérez les erreurs
  //     console.error('Erreur lors de la requête:', error);
  //   });


//   })
// }


    // Fonction pour récupérer les données des comptes depuis le serveur
    // document.getElementById('btnComptes').addEventListener('click', function() {
    
  
    