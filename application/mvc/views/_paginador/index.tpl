<?php if( isset($this->_paginacion) ): ?>


	<div class="text-center">
		<ul class="pagination">
	
<?php 	
if( isset($this->_paginacion['primero']) ): 
	echo '<li>
			<a href="'.$link . $this->_paginacion['primero'].'" aria-label="">
				<span aria-hidden="true" class="glyphicon glyphicon-step-backward"></span> Primero
			</a>
		  </li>';
else:
	echo 'Primero';
endif; 



if( isset($this->_paginacion['anterior']) ): 
	echo '<li>
			<a href="'.$link . $this->_paginacion['anterior'].'" aria-label="Previous">
				<span aria-hidden="true" class="glyphicon glyphicon-chevron-left"></span>
			</a>
		  </li>';
else:
	echo 'Anterior';
endif; 



for( $i=0 ; $i < count($this->_paginacion['rango']) ; $i++ ):
	
	if( $this->_paginacion['actual']==$this->_paginacion['rango'][$i] ):
		echo '<li class="active"><a>'.$this->_paginacion['rango'][$i].'<span class="sr-only">(current)</span></a></li>';
	else: 
		echo 
		'<li>
			<a href="'.$link . $this->_paginacion['rango'][$i].'">'
				.$this->_paginacion['rango'][$i].
			'</a>
		</li>';
 	endif;
endfor; 


if( isset($this->_paginacion['siguiente']) ): 
	echo '<li>
			<a href="'.$link . $this->_paginacion['siguiente'].'" aria-label="Next">
				<span aria-hidden="true" class="glyphicon glyphicon-chevron-right"></span>
			</a>
		  </li>';
else:
	echo 'Siguiente';
endif; 



if( isset($this->_paginacion['ultimo']) ): 
	echo '<li>
			<a href="'.$link . $this->_paginacion['ultimo'].'" aria-label="">
				Último <span aria-hidden="true" class="glyphicon glyphicon-step-forward"></span>
			</a>
		  </li>';
else:
	echo 'Ultimo';
endif; 
?>

		</ul>
	</div>


<?php endif; ?>