import './Dashboard.css';
import api from "../api";
import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";


const Dashboard = () => {
  const navigate = useNavigate();
  const [user, setUser] = useState(null);
  const [role, setRole] = useState("");
  const [userCar, setUserCar] = useState(null);
  const [showDiv, setShowDiv] = useState(false);
  const [showDiv2, setShowDiv2] = useState(false);
  const [userBookings, setUserBookings] = useState([]);

  // 📌 Déconnexion
  const handleLogout = () => {
    localStorage.removeItem("token");
    localStorage.removeItem("userId");
    navigate("/login");
  };

  // 📌 Récupération des informations de l'utilisateur
  useEffect(() => {
    const fetchUser = async () => {
      const userId = localStorage.getItem("userId");
      if (!userId) {
        navigate("/login"); // Redirige si non connecté
        return;
      }
      try {
        const response = await api.get(`/users/${userId}`);
        setUser(response.data);
      } catch (error) {
        console.error("Erreur récupération utilisateur :", error);
        localStorage.removeItem("userId");
        navigate("/login");
      }
    };
    fetchUser();
  }, [navigate] );

 

  const toggleDiv = () => {
    setShowDiv(!showDiv); 
  };

  const toggleDiv2 = () => {
    setShowDiv2(!showDiv2); 
  };
  
  // Changement de rôle de l'utilisateur
  const handleRoleChange = async (selectedRole) => {
    setRole(selectedRole);
    try {
      const userId = user.id;    
      // Mise à jour du rôle avec la clé correcte "role"
      await api.put(`/users/${userId}/role`, { role: selectedRole });   
      alert("Rôle mis à jour !");
      setUser({ ...user, role: selectedRole }); // 🔄 Met à jour l'affichage en React
    } catch (error) {
      console.error("Erreur :", error.response?.data || error);
      alert("Erreur lors de l'enregistrement.");
    }   
  };
  
  // Récupération du véhicule de l'utilisateur
  const fetchVehicule = async (userId) => {
    try {
      const response = await api.get(`/users/${userId}/cars`);
      if (response.data.length > 0) {
        setUserCar(response.data[0]); // 🔹 Stocke le premier véhicule trouvé
      } else {
        setUserCar(null); // 🔹 Aucun véhicule trouvé
      }
    } catch (error) {
      console.error("Erreur lors du chargement du véhicule :", error.response?.data || error);
    }
  };

  // Données du véhicule
  const [vehicule, setVehicule] = useState({
    plaque_immatriculation: "",
    date_immatriculation: "",
    modele: "",
    couleur: "",
    marque: "",
    nb_places: "",
    fumeur: "non",
    animaux: "non",
    preferences: "",
    eco: "non",
  });

  // Ajout du véhicule
  const handleSubmit = async (e) => {
    e.preventDefault();
      
    try {
      const userId = user.id; 
      const response = await api.post("/cars", { user_id: userId, ...vehicule });
      alert("Véhicule ajouté !");
      setShowDiv2(false);
      fetchVehicule(userId); // 🔹 Recharger les véhicules
    } catch (error) {
      console.error("Erreur API :", error.response?.data || error);
      alert("Erreur lors de l'enregistrement.");
    }
  };

  useEffect(() => {
    if (user?.id && (user.role === "chauffeur" || user.role === "chauffeur-passager")) {
      fetchVehicule(user.id);
    }
  }, [user?.id, user?.role]);




  // Récupération des covoiturages
  const fetchUserBookings = async (userId) => {
    try {
      const response = await api.get(`/bookings/user/${userId}`);
      setUserBookings(response.data); // 🔹 Stocke les covoiturages trouvés
    } catch (error) {
      console.error("Erreur récupération des covoiturages :", error.response?.data || error);
    }
  };

  useEffect(() => {
    if (user?.id) {
      fetchUserBookings(user.id);
    }
  }, [user?.id]);

  // Annuler un covoiturage
  const cancelBooking = async (bookingId) => {
    try {
      const userId = localStorage.getItem("userId");
      if (!userId) {
        alert("Vous devez être connecté pour annuler une réservation.");
        return;
      }
      const bookingToCancel = userBookings.find((booking) => booking.booking_id === bookingId);
      const covoituragePrix = bookingToCancel ? bookingToCancel.prix : 0; // Prix du covoiturage
      const response = await api.delete(`/bookings/${bookingId}`);
      alert(response.data.message);
      setUserBookings(userBookings.filter((booking) => booking.booking_id !== bookingId));
      setUser((prevUser) => ({
        ...prevUser,
        credits: prevUser.credits + covoituragePrix, 
      }));
    } catch (error) {
      console.error("❌ Erreur lors de l'annulation :", error.response?.data || error);
      alert("Erreur lors de l'annulation.");
    }
  };


  // Création de covoiturage
  const [covoiturage, setCovoiturage] = useState({
    ville_depart: "",
    ville_arrivee: "",
    date_depart: "",
    prix: "",
    nb_places: "",
    vehicule_id: "",
    photo: "https://www.gravatar.com/avatar/?d=mp",
    statut: "disponible",
    eco: ""
  });

  const handleCovoiturageSubmit = async (e) => {
    e.preventDefault();
    try {
        const response = await api.post("/covoiturages", {
          chauffeur_id: user.id,
          vehicule_id: covoiturage.vehicule_id,
          ville_depart: covoiturage.ville_depart,
          ville_arrivee: covoiturage.ville_arrivee,
          date_depart: covoiturage.date_depart,
          prix: covoiturage.prix,
          nb_places: covoiturage.nb_places,
          photo: covoiturage.photo,
          statut: covoiturage.statut,
          eco: userCar.eco,
    });
    
      alert(response.data.message);
    } catch (error) {
        console.error("Erreur API :", error.response?.data || error);
        alert("Erreur lors de la création du covoiturage.");
      }
  };

  // Récupération covoiturage créés (chauffeur)
  const [driverCovoiturages, setDriverCovoiturages] = useState([]);
  const fetchDriverCovoiturages = async (userId) => {
    try {
      const response = await api.get(`/covoiturages/driver/${userId}`);
      setDriverCovoiturages(response.data);
    } catch (error) {
      console.error("❌ Erreur récupération des trajets chauffeur :", error.response?.data || error);
    }
  };
  useEffect(() => {
    if (user?.id && (user.role === "chauffeur" || user.role === "chauffeur-passager")) {
      fetchDriverCovoiturages(user.id);
    }
  }, [user?.id, user?.role]);


  // Anuller un covoiturage créer (chauffeur)
  const handleCancelCovoiturage = async (covoiturageId) => {
    try {
      await api.put(`/covoiturages/${covoiturageId}/cancel`); // 🔹 API pour annuler
      setDriverCovoiturages((prevCovoiturages) =>
        prevCovoiturages.map((trajet) =>
          trajet.id === covoiturageId ? { ...trajet, statut: "annulé" } : trajet
        )
      );
      alert("Covoiturage annulé avec succès !");
    } catch (error) {
      console.error("❌ Erreur lors de l'annulation :", error.response?.data || error);
      alert("Erreur lors de l'annulation.");
    }
  };


  return (
    <section className="dashboard-page">
    
      <div className="dashboard-main">

        <div className='dashboard-main-title'>   
          <h1>Bienvenue {user?.pseudo}</h1>
          <button onClick={handleLogout}>Déconnexion</button>
        </div>

        <div className="user-infos">
          {user ? (
          <>
            <h2>Vos informations personelles :</h2>
            <img src={user.photo} alt="Photo de profil" className="user-photo" />
            <p><strong>Pseudo :</strong> {user.pseudo}</p>
            <p><strong>Rôle :</strong> {user.role}</p>
            <p><strong>Crédits :</strong> {user.credits}</p>
            <button onClick={toggleDiv}>Changer de role</button>

            {showDiv && (
            <div className='user-role-change'>
              <form onSubmit={(e) => { e.preventDefault(); handleRoleChange(role); }}>
                <label><input type="radio" name="role" value="passager" checked={role === "passager"} onChange={(e) => setRole(e.target.value)} />Passager</label>
                <label><input type="radio" name="role" value="chauffeur" checked={role === "chauffeur"} onChange={(e) => setRole(e.target.value)}/>Chauffeur</label>
                <label><input type="radio" name="role" value="chauffeur-passager" checked={role === "chauffeur-passager"} onChange={(e) => setRole(e.target.value)} />Passager & Chauffeur</label>
                <button type="submit">Valider</button>
              </form>
            </div>
            )}
          </>
          ) : (
            <p>Chargement des informations...</p>
          )}
        </div>

        {(user?.role === "chauffeur" || user?.role === "chauffeur-passager") && (
        <div className="user-car-show">
          <h3>Votre véhicule :</h3>
          <p><strong>Plaque :</strong> {userCar?.plaque_immatriculation || "Non renseignée"}</p>
          <p><strong>Date immatriculation :</strong> {userCar?.date_immatriculation || "Non renseignée"}</p>
          <p><strong>Modèle :</strong> {userCar?.modele || "Non renseigné"}</p>
          <p><strong>Couleur :</strong> {userCar?.couleur || "Non renseignée"}</p>
          <p><strong>Marque :</strong> {userCar?.marque || "Non renseignée"}</p>
          <p><strong>Nombre de places :</strong> {userCar?.nb_places || "Non renseigné"}</p>
          <p><strong>Fumeur :</strong> {userCar?.fumeur === "oui" ? "Autorisé" : "Interdit"}</p>
          <p><strong>Animaux :</strong> {userCar?.animaux === "oui" ? "Autorisé" : "Interdit"}</p>
          <p><strong>Écologique :</strong> {userCar?.eco === "oui" ? "Oui" : "Non"}</p>
          <button onClick={toggleDiv2}>Ajouter un véhicule</button>
        </div>
        )} 

        {showDiv2 && (
        <div className='user-car-register'>
          <h3>Ajoutez votre véhicule :</h3>
          <form onSubmit={handleSubmit}>
            <input type="text" placeholder="Plaque d'immatriculation" required onChange={(e) => setVehicule({ ...vehicule, plaque_immatriculation: e.target.value })} />
            <input type="date" placeholder="Date d'immatriculation" required onChange={(e) => setVehicule({ ...vehicule, date_immatriculation: e.target.value })} />
            <input type="text" placeholder="Modèle" required onChange={(e) => setVehicule({ ...vehicule, modele: e.target.value })} />
            <input type="text" placeholder="Couleur" required onChange={(e) => setVehicule({ ...vehicule, couleur: e.target.value })} />
            <input type="text" placeholder="Marque" required onChange={(e) => setVehicule({ ...vehicule, marque: e.target.value })} />
            <input type="number" placeholder="Nombre de places" required onChange={(e) => setVehicule({ ...vehicule, nb_places: e.target.value })} />
      
            <select onChange={(e) => setVehicule({ ...vehicule, fumeur: e.target.value })}>
              <option value="non">Non-fumeur</option>
              <option value="oui">Fumeur</option>
            </select>
        
            <select onChange={(e) => setVehicule({ ...vehicule, animaux: e.target.value })}>
              <option value="non">Animaux interdits</option>
              <option value="oui">Animaux autorisés</option>
            </select>

            <textarea placeholder="Préférences personnelles" onChange={(e) => setVehicule({ ...vehicule, preferences: e.target.value })}></textarea>
           
            <div className='user-register-status-eco'>
              <h4>Votre véhicule est-il écologique ?</h4>
              <label><input type="radio" name="eco" value="oui" checked={vehicule.eco === "oui"} onChange={(e) => setVehicule({ ...vehicule, eco: e.target.value })}/>Oui</label>
              <label><input type="radio" name="eco" value="non" checked={vehicule.eco === "non"} onChange={(e) => setVehicule({ ...vehicule, eco: e.target.value })}/>Non</label>
            </div>

            <button type="submit">Enregistrer</button>
          </form>
        </div> )}


        {(user?.role === "passager" || user?.role === "chauffeur-passager") && (
        <div className="user-covoiturages">
          <h3>Vos covoiturages réservés :</h3>
          {userBookings.length > 0 ? (
            <ul>
            {userBookings.map((trajet, index) => (
              <li key={trajet.booking_id || `booking-${index}`} className="covoiturage-item">
              <p><strong>Départ :</strong> {trajet.ville_depart}</p>
              <p><strong>Arrivée :</strong> {trajet.ville_arrivee}</p>
              <p><strong>Date :</strong> {trajet.date_depart}</p>
              <p><strong>Prix :</strong> {trajet.prix} crédits</p>
              <p><strong>Chauffeur :</strong> {trajet.chauffeur_nom}</p>
              <p><strong>Écologique :</strong> {trajet.eco === "oui" ? " Oui" : " Non"}</p>
              <button>Démarrer</button>
              <button onClick={() => cancelBooking(trajet.booking_id)}>Annuler</button>
              </li>
            ))}
            </ul>
          ) : (
            <p>Aucune réservation trouvée.</p>
          )}
        </div> )}



        {(user?.role === "chauffeur" || user?.role === "chauffeur-passager") && (
        <div className="driver-covoiturage">

          <h3>Vos covoiturages créer :</h3>

          {Array.isArray(driverCovoiturages) && driverCovoiturages.length > 0 ? (
          <ul>
            {driverCovoiturages.map((trajet) => (
            <li key={trajet.id} className="covoiturage-item">
              <p><strong>Départ :</strong> {trajet.ville_depart}</p>
              <p><strong>Arrivée :</strong> {trajet.ville_arrivee}</p>
              <p><strong>Date :</strong> {trajet.date_depart}</p>
              <p><strong>Prix :</strong> {trajet.prix} crédits</p>
              <p><strong>Places restantes :</strong> {trajet.nb_places}</p>
              <p><strong>Statut :</strong> {trajet.statut === "disponible" ? "✅ Disponible" : "❌ Annulé"}</p>
            {trajet.statut === "disponible" && (
              <button onClick={() => handleCancelCovoiturage(trajet.id)}>Annuler</button>
            )}
            </li>
          ))}
          </ul> ) : (
            <p>Aucun covoiturage trouvé.</p>
          )}

        </div> )}



        {(user?.role === "chauffeur" || user?.role === "chauffeur-passager") && userCar &&(
        <div className='covoiturage-creation'>
          <h3>Créer un covoiturage</h3>

          <form onSubmit={handleCovoiturageSubmit}>
            <input type="text" placeholder="Départ" required onChange={(e) => setCovoiturage({ ...covoiturage, ville_depart: e.target.value })} />
            <input type="text" placeholder="Arrivée" required onChange={(e) => setCovoiturage({ ...covoiturage,ville_arrivee: e.target.value })} />
            <input type="date" required onChange={(e) => setCovoiturage({ ...covoiturage, date_depart: e.target.value })} />
            <input type="number" placeholder="Prix (crédit)" min="1" max="4" required onChange={(e) => setCovoiturage({ ...covoiturage, prix: e.target.value })} />
            <input type="number" placeholder="Nombre de places" required onChange={(e) => setCovoiturage({ ...covoiturage, nb_places: e.target.value })} />
          
            <select required onChange={(e) => setCovoiturage({ ...covoiturage, vehicule_id: e.target.value })}>
              <option value="">Sélectionner un véhicule</option>
              <option key={userCar.id} value={userCar.id}>{userCar.marque} - {userCar.modele}</option>
            </select>

            <button type="submit">Créer</button>
          </form>

        </div> )}













































 
      </div>

    </section>
  );
};

export default Dashboard;


