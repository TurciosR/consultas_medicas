<?php
include("_core.php");   
 	$sql_empresa=_query("SELECT * FROM empresa ");
	$array_empresa=_fetch_array($sql_empresa);
	$nombre_empresa=$array_empresa['nombre'];
	$telefono=$array_empresa['telefono1'];
	$logo_empresa=$array_empresa['logo'];
	
?>
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element" style="border-bottom:3px solid #194160;"> <span>
                        <img alt="image" id='logo_menu' class="logo" src="<?php echo "./".$logo_empresa; ?> " style="width:90%;margin: auto;display: block;">
                    </span>
                </div>
                <div class="logo-element">
                    PB
                </div>
            </li>
            <!--li-->
            <!--a href="index.html"><i class="fa fa-archive"></i> <span class="nav-label">Productos</span> <span class="fa arrow"></span></a-->
            <?php
                        include_once '_core.php';
                       	$id_user=$_SESSION["id_usuario"];
						$admin=$_SESSION["admin"];
						$icono='fa fa-star-o';
						$sql_menus="SELECT id_menu, nombre, prioridad,icono FROM menu WHERE visible='1' order by prioridad";
						$result=_query($sql_menus);
						$numrows=_num_rows($result);
						$main_lnk='dashboard.php';
						if($admin=='1')
						{
							
						echo  "<li class='active'>";
									echo "<a href='dashboard.php'><i class='".$icono."'></i> <span class='nav-label'>Inicio</span></a>";
								echo  "</li>";
						}
						else
						{
							
						echo  "<li class='active'>";
									echo "<a href='dashboard.php'><i class='".$icono."'></i> <span class='nav-label'>Inicio</span></a>";
								echo  "</li>";	
						}
						for($i=0;$i<$numrows;$i++)
						{
							$row=_fetch_array($result);	
							$menuname=$row['nombre'];
							$id_menu=$row['id_menu'];
							$icono=$row['icono'];
							
							if($admin=='1')
							{
								$sql_links="SELECT distinct menu.id_menu, menu.nombre as nombremenu, menu.prioridad, 
								modulo.id_modulo, modulo.nombre as nombremodulo, modulo.descripcion, modulo.filename, usuario.tipo_usuario
								FROM menu, modulo, usuario
								WHERE usuario.id_usuario='$id_user'
								AND usuario.tipo_usuario='1' 
								AND menu.id_menu='$id_menu'
								AND menu.id_menu=modulo.id_menu 
								AND modulo.mostrarmenu='1'";
							}
							else 
							{
								$sql_links="
								SELECT menu.id_menu, menu.nombre as nombremenu, menu.prioridad,
								modulo.id_modulo,  modulo.nombre as nombremodulo, modulo.descripcion, modulo.filename,
								usuario_modulo.id_usuario,usuario.tipo_usuario
								FROM menu, modulo, usuario_modulo, usuario
								WHERE usuario.id_usuario='$id_user'
								AND menu.id_menu='$id_menu'
								AND usuario.id_usuario=usuario_modulo.id_usuario
								AND usuario_modulo.id_modulo=modulo.id_modulo
								AND menu.id_menu=modulo.id_menu
								AND modulo.mostrarmenu='1'";
							}
							$result_modules=_query($sql_links);
							$numrow2=_num_rows($result_modules);
							if($numrow2>0)
							{
								
								echo "<li><a href='".$main_lnk."' class='".strtolower(quitar_tildes($menuname))."'><i class='".$icono."'></i><span class='nav-label'>".$menuname."</span> <span class='fa arrow'></span></a>";
								echo " <ul class='nav nav-second-level'>";								
								for($j=0;$j<$numrow2;$j++)
								{
									$row_modules=_fetch_array($result_modules);
									$lnk=strtolower($row_modules['filename']);
									$modulo=$row_modules['nombremodulo'];
									$id_modulo=$row_modules['id_modulo'];
									echo "<li><a href='".$lnk."'>".ucfirst($modulo)."</a></li>";								
								}							
								echo"</ul>";
								echo" </li>";							
							}			
						}
                        ?>
        </ul>
    </div>
</nav>
<div id="page-wrapper" class="gray-bg">
    <div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <a class="navbar-minimalize minimalize-styl-2 btn btn-white"><i class="fa fa-bars"></i> </a>
            </div>
            <ul class="nav navbar-top-links navbar-left">
                <li>
                    <br>
                    <span class="m-r-sm text-muted welcome-message"><b><?php echo $nombre_empresa ?></b></span>
                </li>
            </ul>

            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message"><b>Bienvenid@</b>
                        <b><?php echo $_SESSION["nombre"] ?> </b></span>
                </li>
                <li>
                    <a data-toggle='modal' href='cambiar_pass.php' data-target='#viewModalpw' data-refresh='true'>
                        <i class="fa fa-lock"></i> Contraseña
                    </a>
                </li>
                <?php if($admin) {?>
                <li>
                    <a href="ayuda.php">
                        <i class="fa fa-question-circle-o"></i> Ayuda
                    </a>
                </li>
                <?php }?>
                <li>
                    <a href="logout.php">
                        <i class="fa fa-sign-out"></i> Salir
                    </a>
                </li>
            </ul>

        </nav>
    </div>

    <div class='modal fade' id='viewModalpw' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'
        aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'></div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
	