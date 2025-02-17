import './Register.css'
import { useState } from "react";
import api from "../api";
import { Link, useNavigate } from "react-router-dom";

const Register = () => {
  const [pseudo, setPseudo] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const navigate = useNavigate();

  const handleRegister = async (e) => {
    e.preventDefault();
    try {
      await api.post("/register", { pseudo, email, password });
      navigate("/login");
    } catch (err) {
      setError("Erreur lors de l'inscription");
    }
  };

  return (

  <section className="sign-in-section">
      
    <div className="sign-in-main">

        <h1>S'inscrire</h1>
        {error && <p style={{ color: "red" }}>{error}</p>}
        <div className="sign-in-inputs">
          <input type="text" placeholder="Pseudo" value={pseudo} onChange={(e) => setPseudo(e.target.value)} required />
          <input type="email" placeholder="Email" value={email} onChange={(e) => setEmail(e.target.value)} required />
          <input type="password" placeholder="Mot de passe" value={password} onChange={(e) => setPassword(e.target.value)} required />
        </div>

        <button type="submit" onClick={handleRegister}>S'inscrire</button>

        <Link to="/Login"><p>Se connecter</p></Link>

    </div>

  </section>

  );
};

export default Register;
