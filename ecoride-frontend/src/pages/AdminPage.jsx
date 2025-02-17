
import './AdminPage.css';
import { useState, useEffect } from "react";
import api from "../api";
import { useNavigate } from "react-router-dom";

const AdminPage = () => {
  const [users, setUsers] = useState([]);
  const [employees, setEmployees] = useState([]);
  const [covoituragesStats, setCovoituragesStats] = useState([]);
  const [creditsStats, setCreditsStats] = useState([]);
  const [totalCredits, setTotalCredits] = useState(0);
  const [reviews, setReviews] = useState([]);
  const [reportedTrips, setReportedTrips] = useState([]);

  const navigate = useNavigate();

  // 🔹 Vérifier si l'utilisateur est admin
  useEffect(() => {
    const fetchAdminData = async () => {
      const userId = localStorage.getItem("userId");
      if (!userId) {
        navigate("/login");
        return;
      }

      try {
        const response = await api.get(`/users/${userId}`);
        if (response.data.role !== "admin") {
          alert("Accès refusé.");
          navigate("/");
        }
      } catch (error) {
        console.error("Erreur vérification admin :", error);
        navigate("/login");
      }
    };

    fetchAdminData();
  }, [navigate]);

  // 🔹 Récupérer les utilisateurs
  useEffect(() => {
    const fetchUsers = async () => {
      try {
        const response = await api.get("/users");
        setUsers(response.data);
      } catch (error) {
        console.error("Erreur récupération utilisateurs :", error);
      }
    };
    fetchUsers();
  }, []);

  // 🔹 Récupérer les employés
  useEffect(() => {
    const fetchEmployees = async () => {
      try {
        const response = await api.get("/employees");
        setEmployees(Array.isArray(response.data) ? response.data : []);
      } catch (error) {
        console.error("❌ Erreur récupération employés :", error);
        setEmployees([]); // ✅ Définit un tableau vide en cas d'erreur
      }
    };
  
    fetchEmployees();
  }, []);

  // 🔹 Récupérer les statistiques
  useEffect(() => {
    const fetchStats = async () => {
      try {
        const response1 = await api.get("/stats/covoiturages");
        const response2 = await api.get("/stats/credits");
        const response3 = await api.get("/stats/total-credits");

        setCovoituragesStats(response1.data);
        setCreditsStats(response2.data);
        setTotalCredits(response3.data.total);
      } catch (error) {
        console.error("Erreur récupération statistiques :", error);
      }
    };
    fetchStats();
  }, []);

  // 🔹 Récupérer les avis en attente
  useEffect(() => {
    const fetchReviews = async () => {
      try {
        const response = await api.get("/reviews");
        setReviews(response.data);
      } catch (error) {
        console.error("Erreur récupération avis :", error);
      }
    };
    fetchReviews();
  }, []);

  // 🔹 Récupérer les trajets signalés
  useEffect(() => {
    const fetchReportedTrips = async () => {
      try {
        const response = await api.get("/reported-trips");
        setReportedTrips(response.data);
      } catch (error) {
        console.error("Erreur récupération trajets signalés :", error);
      }
    };
    fetchReportedTrips();
  }, []);

  return (
    <section className="admin-section">
      <div className="admin-page">

        <h1>⚙️ Panneau d'Administration</h1>

        <div className="user-management">
          <h2>👥 Gestion des Utilisateurs</h2>
            <ul>
              {users.map((user) => (
                <li key={user.id}>
                  - {user.pseudo} ({user.role}) - {user.email}
                  <button>⚠ Suspendre</button>
                  <button>❌ Supprimer</button>
                </li>
              ))}
            </ul>
        </div>


        <div className="employe-management">
          <h2>🏢 Gestion des Employés</h2>
            <ul>
              {employees.map((emp) => (
                <li key={emp.id}>
                  - {emp.pseudo} ({emp.role}) - {emp.email}
                  <button>⚠ Suspendre</button>
                  <button>❌ Supprimer</button>
                </li>
              ))}
            </ul>
        </div>

      
        <div className="stat-management">
          <h2>📊 Statistiques</h2>
          <p>📈 Covoiturages par jour : ...</p> 
          <p>💰 Crédits gagnés par jour : ...</p>
          <p>🏦 Total de crédits dans la plateforme : ...</p>
        </div>

      
        <div className="passenger-management">
          <h2>📝 Avis des Passagers</h2>
            {reviews.length > 0 ? (
              <ul>
                {reviews.map((review) => (
                  <li key={review.id}>
                    {review.user_pseudo} - {review.comment}
                    <button>✅ Valider</button>
                    <button>❌ Refuser</button>
                  </li>
                ))}
              </ul>
            ) : ( <p>Aucun avis en attente.</p> )}
        </div>

      
        <div className="route-management">
          <h2>⚠️ Trajets Signalés</h2>
            {reportedTrips.length > 0 ? (
              <ul>
                {reportedTrips.map((trip) => (
                  <li key={trip.id}>
                    {trip.ville_depart} → {trip.ville_arrivee} ({trip.date_depart})
                    <button>🧐 Voir Détails</button>
                  </li>
                ))}
              </ul>
            ) : ( <p>Aucun trajets signalé.</p> )}
        </div>


      </div>
    </section>
  );
};

export default AdminPage;
