jQuery(document).ready(function( $ ) {var i = $('.mwzfw-codhooks').attr('index');
  /*=====================================================*/
  /*=====================================================*/
  /*=====================================================*/
  var codmax = 0;
  $('.mwzfw-codhooks').each(function(){
    var value = parseInt($(this).attr('index'));
    codmax = (value > codmax) ? value : codmax;
  });
  $('.mwzfw-add-cod-hook').click(function(){
    $(".mwzfw-add-cod-hook-line").prepend(`
    <div>
      <label>Hook: </label>
      <input
        type="password"
        name="mwzfw_settings[codhooks][`+ (codmax+1) +`]"
        value="">
      <label>Description: </label>
      <input
        type="text"
        name="mwzfw_settings[codhooksdescription][`+ (codmax+1) +`]"
        class="mwzfw-codhooks"
        index=`+ (codmax+1) +`
        value="">
      <span class="dashicons dashicons-dismiss mwzfw-remove-hook"></span>
    </div>
    `);
    codmax += 1;
    $('.mwzfw-remove-hook').click(function(){
      $(this).parent('div').html('');
    })
  });
  /*=====================================================*/
  /*=====================================================*/
  /*=====================================================*/

  var notcodmax = 0;
  $('.mwzfw-notcodhooks').each(function(){
    var value = parseInt($(this).attr('index'));
    notcodmax = (value > notcodmax) ? value : notcodmax;
  });
  $('.mwzfw-add-notcod-hook').click(function(){
    $(".mwzfw-add-notcod-hook-line").prepend(`
    <div>
      <label>Hook: </label>
      <input
        type="password"
        name="mwzfw_settings[notcodhooks][`+ (notcodmax+1) +`]"
        value="">
      <label>Description: </label>
      <input
        type="text"
        name="mwzfw_settings[notcodhooksdescription][`+ (notcodmax+1) +`]"
        class="mwzfw-notcodhooks"
        index=`+ (notcodmax+1) +`
        value="">
      <span class="dashicons dashicons-dismiss mwzfw-remove-hook"></span>
    </div>
    `);
    notcodmax += 1;
    $('.mwzfw-remove-hook').click(function(){
      $(this).parent('div').html('');
    })
  });

  /*=====================================================*/
  /*=====================================================*/
  /*=====================================================*/
  //Remove Hook.
  $('.mwzfw-remove-hook').click(function(){
    $(this).parent('div').html('');
  })
});
