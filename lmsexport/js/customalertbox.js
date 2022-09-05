
function customAlert(userOptions, actionWhenConfirmed, actionWhenDismissed) {
    // Default Options
    let options = {
      style : 'info',
      heading : 'Notice',
      type : 'alert',
      text : ''
    };
  
    // User options
    if(userOptions) {
      $.each(userOptions, function(option, value){
        options[option] = value;
      });
    }
  
    $.get('/confirmation-dialogue.php', options, function(html) {
        // first remove the confirmation if it exists
        if( $('#confirmation-overlay').length ) {
          $('#confirmation-overlay').remove();
        }
        $('body').append(html);
      })
      .done(function(){
  
        $('#confirmation-confirm').click(function(){
          if(typeof actionWhenConfirmed === 'function' ) {
            actionWhenConfirmed();
          }
          $('#confirmation-overlay').remove();
        });
  
        $('#confirmation-dismiss').click(function(){
          if( typeof actionWhenDismissed === 'function' ) {
            actionWhenDismissed();
          }
          $('#confirmation-overlay').remove();
        });
  
      }).fail(function(data) {
          alert('Something went wrong.');
      });
  }
  