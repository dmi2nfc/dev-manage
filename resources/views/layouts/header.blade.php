<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand" href="#">St13-Bookkeeping</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarColor02">
        <ul class="navbar-nav mr-auto">
          <router-link tag="li" class="nav-item" active-class="active" to="/workers" exact><a class="nav-link">Workers</a></router-link>
          <router-link tag="li" class="nav-item" active-class="active" to="/customers"><a class="nav-link">Customers</a></router-link>
          <router-link tag="li" class="nav-item" active-class="active" to="/projects"><a class="nav-link">Projects</a></router-link>
          <router-link tag="li" class="nav-item" active-class="active" to="/income"><a class="nav-link">Income</a></router-link>
          <router-link tag="li" class="nav-item" active-class="active" to="/statistics"><a class="nav-link">Statistics</a></router-link>
        </ul>
        {{-- <form class="form-inline">
          <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Search</button>
        </form> --}}
      </div>
    </div>
</nav>