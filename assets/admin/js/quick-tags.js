QTags.addButton( 'qtags-one', 'U', '<u>', '</u>' );
QTags.addButton( 'qtags-two', 'google map', '[gmap]', '[/gmap]' );
QTags.addButton( 'qtags-three', 'Insert Name', qtags_btn_three );
QTags.addButton( 'qtags-four', 'font awesome', qtags_btn_four );

function qtags_btn_three() {
      let name = prompt('What\'s your name');
      let greetings = `Hello ${name}`;
      QTags.insertContent( greetings );
}

function qtags_btn_four() {
      tb_show('Fontawesome', qtls.preview);
}

function insert( icon ) {
      tb_remove();
      QTags.insertContent(`<i class='fa ${icon}'></i>`);
}