<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - PIIGPI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Panel Administrativo - PIIGPI</h1>
            <div class="user-info">
                <span>Bienvenido, {{ Auth::guard('personal')->user()->nombre }}</span>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
        </header>

        <main>
            <div class="dashboard-cards">
                <div class="card">
                    <h3>Proyectos en Revisión</h3>
                    <p>Gestiona los proyectos pendientes de aprobación</p>
                    <a href="{{ route('proyectos.index') }}" class="btn">Ver Proyectos</a>
                </div>

                <div class="card">
                    <h3>Investigadores</h3>
                    <p>Administra la información de investigadores</p>
                    <a href="#" class="btn">Gestionar</a>
                </div>
            </div>
        </main>
    </div>

    <style>
        body { font-family: 'Montserrat', sans-serif; margin: 0; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        header { display: flex; justify-content: space-between; align-items: center; background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; margin: 0; }
        .user-info { display: flex; align-items: center; gap: 15px; }
        .btn-logout { background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; }
        .dashboard-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .card h3 { color: #2c3e50; margin-bottom: 10px; }
        .btn { display: inline-block; background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; margin-top: 15px; }
    </style>
</body>
</html>
