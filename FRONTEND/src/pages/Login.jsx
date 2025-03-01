import './Login.css'
import { useState } from "react";
import api from "../api";
import { Link, useNavigate } from "react-router-dom";

const Login = () => {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const navigate = useNavigate();

  const handleLogin = async (e) => {
    e.preventDefault();
    try {
      const response = await api.post("/login", { email, password });
      localStorage.setItem("token", response.data.token);
      localStorage.setItem("userId", response.data.user.id); // Stocke l'ID de l'utilisateur
      if (response.data.user.role === "admin") {
        navigate("/admin"); 
      } else {
        navigate("/dashboard"); 
      }      
    } catch (err) {
      setError("Email ou mot de passe incorrect");
    }
  };

  return (
    <>
    <section className='login-section'>

      <div className="login-main">

        <h1>Connexion</h1>
        {error && <p style={{ color: "red" }}>{error}</p>}
        <div className="login-inputs">
          <input type="mail" placeholder="E-mail" value={email} onChange={(e) => setEmail(e.target.value)} required/>
          <input type="password" placeholder= "Mot de passe" value={password} onChange={(e) => setPassword(e.target.value)} required />
        </div>

        <button type='submit' onClick={handleLogin}>Se connecter</button>

        <Link to="/register"><p>S'inscrire</p></Link>

      </div>    
    
    </section>
    </>
  );
};

export default Login;
;
