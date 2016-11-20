//<![CDATA[
/*!
*/

/*!
 *	triggerSlideIntro
 *	fonction qui lance le sliding des images pour la page d'accueil
 *	
 *	@argument :	none
 *	@returns :	true
 *	@requires :	#contenu_page img.intro_pics {class, images qui vont slider}
 */
var anim_en_cours = false;
function triggerSlideIntro(){
	if(isElementById('contenu_page')){
		$('#contenu_page').css('overflow','hidden');
		if($('.intro_pics').length > 1){
			$('#contenu_page div.intro_pics:gt(0)').hide();
			setInterval(function(){
			$('#contenu_page :first-child').fadeOut(500)
				.next('div.intro_pics').fadeIn(500)
				.end().appendTo('#contenu_page');}, 
			3000);
		}
		$('#contenu_page').css("bottom","40px");
	}
	return true;
}

/*!
 *	getWidthPictures
 *	fonction qui retourne la width des divs img
 *	
 *	@argument :	none
 *	@returns :	width			{int,	width (max) des images affichees}
 *	@requires :	#conteneur_hd li	{class,	conteneur des images hd}
 */
function getWidthPictures(){
	if(isElementById('conteneur_hd')){
		return parseInt($('#conteneur_hd li').first().width(),10);
	}else{
		console.log('fail getWidthPictures()');
		return 0;
	}
}
/*!
 *	next_img
 *	fonction qui est declenchee a chaque clic sur une photo, pour afficher les suivantes/precedentes
 *	
 *	@argument :	position_target		{int,	classement de l'image a laquelle on veut aller (fact : toujours pair)}
 *	@returns :	true
 *	@requires :	#conteneur_hd		{id,	le conteneur des images}
 */
var animation_en_cours = false;
var current_position = 1;			// l'index de l'image sur laquelle on est
var speed_anim = 1;				// en ms
function next_img(position_target){
	if(isElementById('conteneur_hd')){
		// on verifie la valeur du position target
		if(position_target<1){position_target=1;}
		if(position_target>$('.img_big').length){
			position_target=$('.img_big').length-1;
		}
		
		
		var current_width_img = getWidthPictures();
		// au cas ou , on force le conteneur d'avoir la taille de deux images.
		$('#conteneur_hd_conteneur').width(current_width_img*2);
		// la position desiree :
		var position_target_px = position_target*current_width_img;
		if(!animation_en_cours){
			animation_en_cours = true;
			scroll(current_position,position_target);
			setHash(position_target);
		}else{
			// on est deja en train de faire une anim
		}
	}
	return true;
}
/*!
 *	scroll
 *	fonction recursive qui s'occupe de scroller les photos
 *	
 *	@argument :	position_depart		{int,	index de l'image a laquelle on est}
 *			position_finale		{int,	index de l'image a laquelle on veut aller}
 *	@returns :	true
 *	@requires :	#conteneur_hd		{id,	le conteneur des images}
 */
function scroll(position_depart,position_finale){
//console.log('scroll('+position_depart+','+position_finale+')');
	if(isElementById('conteneur_hd')){
		var position_finale_px = (position_finale-1)*getWidthPictures();						// -1 car la premiere image a 1 comme index
		$("#conteneur_hd").animate({
			marginLeft: -position_finale_px
		}, speed_anim, "swing", function() {
		// Animation complete.
			current_position = position_finale;
			animation_en_cours = false;
			if(current_position>1){
				// on peut afficher la fleche 'img precedente'
				$('#a_left').css('visibility','visible').fadeIn('fast').attr("onclick","next_img("+(current_position-2)+")");
			}else{
				$('#a_left').fadeOut('fast',function(){
					$('#a_left').css('visibility','hidden');
				});
			}
			if(current_position < ($('.img_big').length-1)){
				// on peut afficher la fleche 'img suivante'
				$('#a_right').css('visibility','visible').fadeIn('fast').attr("onclick","next_img("+(current_position+2)+")");
			}else{
				$('#a_right').fadeOut('fast',function(){
					$('#a_right').css('visibility','hidden');
				});
			}
		});
	}
	return true;
}
/*!
 *	getSizeWindows
 *	fonction qui initialise la taille de l'ecran et la stocke dans des inputs
 *	
 *	@argument :	none
 *	@returns :	true
 *	@requires :	#width_windows {input, hauteur de l'ecran}
 *			#height_windows {input, largeur de l'ecran}
 */
function getSizeWindows(){
	var largeur_fenetre = $('#width_windows');
	var largeur = 0;
	if(window.innerWidth){
		largeur = window.innerWidth;
	}else{
		if(document.body && document.body.offsetWidth){
			largeur = document.body.offsetWidth;
		}
	}
	largeur_fenetre.val(largeur);

	var hauteur_fenetre = $('#height_windows');
	var hauteur = 0;
	if(window.innerWidth!= undefined){
		hauteur = window.innerHeight;
	}else{
		var B= document.body, 
		D= document.documentElement,
		A= document.activeElement;
			// hauteur = Math.max(D.clientHeight, B.clientHeight, A.clientHeight);
		hauteur = D.clientHeight;
	}
	hauteur_fenetre.val(hauteur);
	checkDim();
	if(isElementById('conteneur_hd_conteneur')){
		next_img(current_position);
	}
	return true;
}
/*!
 *	checkDim
 *	fonction qui redimentionne le site s'il est hors page. On se focalise sur la hauteur uniquement
 *	
 *	@argument :	none
 *	@returns :	true
 *	@requires :	#width_windows {input, hauteur de l'ecran}
 *			#height_windows {input, largeur de l'ecran}
 */
function checkDim(){
	var hauteur_site_defaut = 970;				// hauteur maximale possible
	var largeur_site_defaut = 1200;				// largeur maximale possible
	var hauteur_site_min = 390;				// hauteur minimale possible
	var largeur_site_min = 498;				// largeur minimale possible
	var hauteur_ecran = $('#height_windows').val();						// hauteur de la fenetre d'affichage de l'ecran
	var largeur_ecran = $('#width_windows').val();						// hauteur de la fenetre d'affichage de l'ecran
	var hauteur_site_affichage = hauteur_ecran-2*40;	// hauteur du site optimale
	var largeur_site_affichage = largeur_ecran-2*5	// largeur du site optimale
	var hauteur_menu = 70;					// hauteur du menu
	if(hauteur_site_defaut/largeur_site_defaut > (hauteur_ecran-2*40)/(largeur_ecran-2*5)){
		// ecran plus large, on redimentionne en hauteur
		hauteur_site_affichage = Math.max(hauteur_site_min,hauteur_site_affichage);
		largeur_site_affichage = Math.max(largeur_site_min,(hauteur_site_affichage-hauteur_menu)*6/9*2);
	}else{
		// ecran plus haut, on redimentionne en largeur
		largeur_site_affichage = Math.max(largeur_site_min,largeur_site_affichage);
		hauteur_site_affichage = Math.max(hauteur_site_min,(largeur_site_affichage/2/6*9+hauteur_menu)-10);
	}
	$('#conteneur').height(hauteur_site_affichage).width(largeur_site_affichage);
	if(isElementById('contenu_page_conteneur')){
		// on affiche les cover albums. elle doivent pouvoir accepter deux photos cote a cote
		var margin_v = Math.min(2,100);
		var margin_h = Math.min(4,100);
		var nombre_apl = 4;
		var hauteur_contenu_page_conteneur = hauteur_site_affichage-hauteur_menu-40;
		$('#contenu_page_conteneur').height(Math.min(hauteur_contenu_page_conteneur,970));
		var largeur_min_album = Math.floor($('#contenu_page_conteneur_hackscroll').width()/nombre_apl);
		var largeur_min_album_sans_marges = Math.floor((100-2*margin_h)*largeur_min_album/100);
		if(largeur_min_album_sans_marges%2==1){
			largeur_min_album_sans_marges--;
		}
		var hauteur_min_album = Math.floor(largeur_min_album_sans_marges*9/6);
		$('.min_albums').height(hauteur_min_album/2+30)
				.width(largeur_min_album_sans_marges)
				.css({
					'margin-left'	: Math.floor(largeur_min_album*margin_h/100)+'px',
					'margin-right'	: Math.floor(largeur_min_album*margin_h/100)+'px',
					'margin-top'	: Math.floor($('.min_albums').height()*margin_v/100)+'px',
					'margin-bottom'	: Math.floor($('.min_albums').height()*margin_v/100)+'px'
				})
		;
		$('.min_albums_img').width(Math.floor(largeur_min_album_sans_marges/2));
		if(!isElementById('contact')){
			var largeur_cpch = $('#contenu_page_conteneur_hackscroll').width();
			// on place la scrollbar completement a droite
			//$('#contenu_page_conteneur').width(Math.floor((largeur_ecran-largeur_site_affichage)/2+largeur_site_affichage));
			$('#contenu_page_conteneur').width(Math.floor((largeur_ecran-largeur_site_affichage)+largeur_site_affichage));
			$('#contenu_page_conteneur').css("left","-"+(Math.floor((largeur_ecran-largeur_site_affichage)/2))+"px");
			$('#contenu_page_conteneur_hackscroll').width(largeur_cpch);
		}
	}
	if(isElementById('conteneur_hd_conteneur')){
		// on affiche les HD
		var largeur_opt_hd = Math.floor((hauteur_site_affichage-hauteur_menu-40)*6/9);
		$('#conteneur_hd_conteneur').height(hauteur_site_affichage-hauteur_menu-40).width(largeur_opt_hd*2).css('left',(Math.floor(($('#contenu_page').width()-(largeur_opt_hd*2))/2))+'px');
		$('#conteneur_hd').width($('.img_big').length*largeur_opt_hd);
		$('.img_big').width(largeur_opt_hd);
		$('#back').css('right','-'+Math.min(100,(largeur_ecran-largeur_site_affichage)/2)+'px');
		$('#a_left').css('left','-'+Math.min(28,(largeur_ecran-largeur_site_affichage)/2)+'px');
		$('#a_right').css('right','-'+Math.min(28,(largeur_ecran-largeur_site_affichage)/2)+'px');
		$('#legend')	.width(largeur_opt_hd*2)
				.css({
					'bottom':0,
					'left'	:(Math.floor(($('#contenu_page').width()-(largeur_opt_hd*2))/2))+'px'
				});
	}
	if(isElementById('menu')){
		// la taille des polices du menu
		var nbr_menu = $('.main_menu li a').length;
		var police_size = 10;
		var largeurMaxPossible = largeur_site_affichage-$('#menu .title').width();
		// on reinitialise les valeurs -
		$('.main_menu').css('font-size',police_size+'px');
		$('#js_menu_width').css('font-size',police_size+'px');
		$('.main_menu span').css('font-size',(police_size+3)+'px');
		$('#js_menu_width span').css('font-size',(police_size+3)+'px');
		// fonction internet qui calcule la place que prend le menu (pour une police donnee)
		function calculWidth(){
			var largeur = 0;
			$('.main_menu>li').each(function() {
				$('#js_menu_width').html($(this).children().html());
				largeur+=$('#js_menu_width').width()+40;	
			});
			return largeur;
		}
		// loop pour calculer la police la plus grande possible
		while(calculWidth()>largeurMaxPossible && police_size>0){
			police_size--;
			$('.main_menu').css('font-size',police_size+'px');
			$('#js_menu_width').css('font-size',police_size+'px');
			$('.main_menu span').css('font-size',(police_size+3)+'px');
			$('#js_menu_width span').css('font-size',(police_size+3)+'px');
		}
	}
	if(isElementById('contact')){
		var hauteurContact = $('#contact').height();
		var hauteurConteneur = $('#contenu_page_conteneur').height();
		var margin = 0;
		if(hauteurConteneur>hauteurContact){
			margin=Math.floor((hauteurConteneur-hauteurContact)/2);
		}
		$('#contact').css('marginTop',margin+'px');
		$('#contact').html(replaceAll($('#contact').html(),' (<span class="ml">ã</span>) ','@'));
		$('#contact').html(replaceAll($('#contact').html(),' (<span class="ml">&#227;</span>) ','@'));
	}
}
/*!
 *	loadHDs
 *	fonction qui lance le procede de chargement des HDs
 *	
 *	@argument :	none
 *	@returns :	none
 *	@requires :	.img_big	{class,		conteneur des images}
 */
function loadHDs(){
	var num_img = $('.img_big').length;
	if(num_img>0){
		loadHD(1);
	}
}
/*!
 *	loadHD
 *	fonction qui charge une HD specifique
 *	
 *	@argument :	num		{int,		indice de l'image traitee}
 *	@returns :	none
 *	@requires :	#img_+num	{class,		conteneur des images}
 */
function loadHD(num){
	if(isElementById('img_'+num)){
		var hd_link = ($('.img_big').eq(num-1).css('background-image'));
		hd_link = hd_link.replace('url("','').replace('")','').replace('/min/','/');
		hd_link = hd_link.replace('url(','').replace(')','');	// hack safari
		var image_HD = creerObjetImage(hd_link);
		image_HD.onload = function(){
			// une fois chargée, on l'affiche
			$('#img_'+num+'').css('background-image','url(\''+hd_link+'\')');
			$('#img_'+num+' .loading').fadeOut("fast");
			if(num+1<=$('.img_big').length){
				loadHD(num+1);
			}
		};
		image_HD.onerror = function(){
			console.log('fail loading hd');
		}


	}
}

/*!
 *	affichePage
 *	fonction qui affiche deux lignes specifiques des albums (DISABLED)
 *	
 *	@argument :	num			{int,		indice de la page traitee}
 *	@returns :	none
 *	@requires :	#contenu_page_conteneur	{id,		conteneur des albums}
 */
function affichePage(num){
	if(isElementById('contenu_page_conteneur')){
		var nombre_page_possible = Math.ceil($('.min_albums').length/8);
		if(nombre_page_possible>1){
			var cpt_page = 0;
			if(!isElementById('page_'+cpt_page+'')){
				var contain='';
				var final_html ='';
				$('.min_albums').each(function( index ) {
					if(index%8==0){
						cpt_page++;
						if(cpt_page!=1){
							final_html+=contain+'</div>';
							contain='';
						}
						contain+='<div class="page" id="page_'+cpt_page+'">';
					}
					contain+=$(this)[0].outerHTML;
				});
			}
			final_html+=contain+'</div><div id="counter_page">page : ';
			for(var i=1;i<=nombre_page_possible;i++){
				final_html+='<a href="#'+i+'" class="pointer_special page_a" id="page_'+i+'_a" title="" onclick="affichePage('+i+');">'+i+'</a>'
			}
			final_html+='</div>';
			$('#contenu_page_conteneur').html(final_html);
			$('.page').css({
				'visibility':'hidden',
				'display':'none'
				});
			$('#page_'+num).css({
				'visibility':'visible',
				'display':'block'
			});
			$(".page_a").css({
				'font-weight':'normal',
				'text-decoration': 'none'
			});
			$("#page_"+num+"_a").css({
				'font-weight':'bold',
				'text-decoration': 'underline'
			});
		}
	}
}

 //]]>
