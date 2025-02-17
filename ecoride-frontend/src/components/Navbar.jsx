import './Navbar.css'
import { Link } from "react-router-dom";

const Navbar = () => {


  return (
  <section className="navbar"> 
    <nav>
      <ul>
        <li><Link to="/">Accueil</Link></li>
        <li><Link to="/Covoiturages">Covoiturage</Link></li>
        <li><Link to="/dashboard">Espace utilisateur</Link></li>
      </ul>
    </nav>
    </section>   
  );
};

export default Navbar;