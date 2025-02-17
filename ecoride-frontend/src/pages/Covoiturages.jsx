import './Covoiturages.css';
import { useState } from "react";
import api from "../api";

const Covoiturages = () => {
  const [villeDepart, setVilleDepart] = useState("");
  const [dateDepart, setDateDepart] = useState("");
  const [trajets, setTrajets] = useState([]);
  const [rechercheEffectuee, setRechercheEffectuee] = useState(false);



  // Recherche de covoiturages
  const handleSearch = async (e) => {
    e.preventDefault();
    setRechercheEffectuee(true); 

    try {
      const response = await api.get(`/covoiturages/search`, { 
        params: { ville_depart: villeDepart, date_depart: dateDepart }
      });
      setTrajets(response.data || []);
    } catch (error) {
      console.error("❌ Erreur lors de la recherche :", error.response?.data || error);
      alert("Aucun trajet trouvé. Essayez une autre date.");
    }
  };

  // Booking de covoiturage
  const createBooking = async (covoiturageId) => {
    try {
      const userId = localStorage.getItem("userId");
      if (!userId) {
        alert("Vous devez être connecté pour participer à un covoiturage.");
        return;
      }           
      const response = await api.post("/bookings", { user_id: userId, covoiturage_id: covoiturageId });
      alert(response.data.message);
    } catch (error) {
      console.error("❌ Erreur lors de la réservation :", error.response?.data || error);
      alert("Crédits insuffisant !");
    }   
  };


  //console.log(trajets.prix);


  return (
  <section className="covoiturages-section">

    <div className='covoiturages-main'>

      <div className='covoiturages-search'>
        <form onSubmit={handleSearch}>
          <h3>Rechercher un covoiturage</h3>
          <input type="text" placeholder="Ville de départ" value={villeDepart} onChange={(e) => setVilleDepart(e.target.value)} required />
          <input type="date" value={dateDepart} onChange={(e) => setDateDepart(e.target.value)} required />
          <button type="submit">Rechercher</button>
        </form>
      </div> 

      <div className='covoiturages-display'>
        {rechercheEffectuee ? (
          trajets.length > 0 ? (
            trajets.map((trajet) => (
              <div key={trajet.id} className="trajet">

                <div className='trajet-photo'>
                  <img src={trajet.photo} alt="Photo du chauffeur" />
                  <div className='trajet-driver-info'>
                    <p><strong>{trajet.chauffeur_nom}</strong></p>
                    <p>Note du chauffeur : {trajet.note_moyenne} ⭐</p>
                  </div>
                </div>

                <div className='trajet-description'>
                  <p><strong>Départ :</strong> {trajet.ville_depart}</p>
                  <p><strong>Arrivée :</strong> {trajet.ville_arrivee}</p>
                  <p><strong>Date de départ :</strong> {trajet.date_depart}</p>
                  <p><strong>Trajet écologique :</strong> {trajet.eco === "oui" ? "Oui" : "Non"}</p>
                  <button>Détails</button>
                </div>

                <div className='trajet-price'>  
                  <p><strong>Places restantes :</strong> {trajet.nb_places}</p>
                  <p><strong>Prix :</strong> {trajet.prix} crédits</p>
                  <button onClick={() => createBooking(trajet.id)}>Participer</button>
                </div> 

              </div>
            ))
          ) : (
            <p id='no-match-p'>Aucun trajet trouvé. Essayez une autre date.</p>
          )
        ) : (
          <p id='user-guide'>Veuillez entrer une ville et une date pour voir les covoiturages disponibles.</p>
        )}
      </div>


    </div>

  </section>  
  );
};

export default Covoiturages;

