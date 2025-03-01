import './Home.css'
const Home = () => {
    return (
      <section className="homepage">

        <div className='home-research'>
          <div className='home-presentation'>
            <h1>Bienvenue sur EcoRide !</h1>
            <p>Trouvez des covoiturages écologiques.</p>
          </div>

          <div className='home-search'>
            <input type='text' placeholder="Adresse de départ"/>
            <input type='text' placeholder="Adresse d'arrivée"/>
            <button type='submit'>Lancer ma recherche</button>
          </div>
        </div>
      

      </section>
    );
  };
  
  export default Home;
  