<nav class="navbar navbar-expand-lg navbar-dark bg-dark" >
  <a class="navbar-brand" href="/">LaraTodo</a>
  <button class="navbar-toggler navbar-toggler-right collapsed" type="button" data-toggle="collapse" data-target="#navb" aria-expanded="false">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="navbar-collapse collapse" id="navb" style="">
    <ul class="navbar-nav ml-auto">
      @guest
      
     
        <li class="nav-item">
          <a class="nav-link" href="/login">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/register">Register</a>
        </li>   
      @endguest
  
      @auth
        <li class="nav-item">
          <a class="nav-link" href="javascript:void(0)">Welcome {{auth()->user()->name}}</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link logoutBtn" href="javascript:void(0)" data-href="/logout">Logout</a>
        </li>   
      @endauth
   
    </ul>
 
  </div>
</nav>