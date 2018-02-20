var jx = {
  method: 'GET',
  url: 'Calendar.php',
  data: {}
}

function _do() {
  $.ajax(jx).done(function(res) {
    $("#content").html(res);
  }).fail(function(xhr, stat, err) {
    $("#content").html(err);
  });
}

function removeCategory(id, n) {
  $("#confirm-text").text('Ezt fogod törölni: "'+n+'"!');
  $("#confirm").toggleClass("w3-show");
  $("#confirm-ok").off();

  $("#confirm-ok").click(function(event) {
    event.preventDefault();
    $("#confirm").toggleClass("w3-show");
    hideCategory(id);
    $("#confirm-text").text("");
  });
}

function hideCategory(id) {
  jx.data = {
    calendarClass: 'Category',
    filter: JSON.stringify({
      id: id
    }),
    _call: 'hideCategory'
  };
  _do();
}

function editCategory(id=0, n, s) {
  $("#category-popup").toggleClass("w3-show");
  $("#category-form-ok").off();

  $("#category-name").val(n);
  $("#category-style").val(s);

  $("#category-form-ok").click(function(event) {
    event.preventDefault();

    let new_n = $("#category-name").val();
    let new_s = $("#category-style").val();

    if ( (new_n=='') || (new_s=='') ) {
      if ($("#category-fields-error").hasClass("w3-show")===false) {
        $("#category-fields-error").addClass("w3-show");
      }
      $("#category-fields-error").text("Érvénytelen kategória vagy szín!");
      return;
    }

    $("#category-popup").toggleClass("w3-show");
    if (id) {
      updateCategory(id, new_n, new_s);
    }
    else {
      newCategory(new_n, new_s);
    }
    $("#confirm-text").text("");
  });
}

function newCategory(name, style) {
  jx.data = {
    calendarClass: 'Category',
    filter: JSON.stringify({
      name: name,
      style: style
    }),
    _call: 'newCategory'
  };
  _do();
}

function updateCategory(id, name, style) {
  jx.data = {
    calendarClass: 'Category',
    filter: JSON.stringify({
      id: id,
      name: name,
      style: style
    }),
    _call: 'updateCategory'
  };
  _do();
}

function listCategories() {
  jx.data = {
    calendarClass: 'Category',
    filter: '',
    _call: 'listCategories'
  };
  _do();
}

function getCategories() {
  jx.data = {
    calendarClass: 'Category',
    filter: '',
    _call: 'getCategories'
  };
  $.ajax(jx).done(function(res) {
    $("#event-category-label").after(res);
  }).fail(function(xhr, stat, err) {
    $("#content").html(err);
  });
}

function getDateFormattedForInput(dt_in) {
  let dt = new Date(dt_in);
  let year = dt.getFullYear();
  let month = (((dt.getMonth()+1)<10)?"0"+(dt.getMonth()+1):dt.getMonth());
  let day = (dt.getDate()<10)?"0"+dt.getDate():dt.getDate();
  return year + "-" + month + "-" + day;
}

function editEvent(id=0, start, subject, category_id) {
  $("#event-popup").toggleClass("w3-show");
  $("#event-form-ok").off();

  $("#event-subject").val(subject);
  $("#event-start").val(getDateFormattedForInput(start));
  $("#event-end").val("");
  $("#event-category").val(category_id);

  $("#event-form-ok").click(function(event) {
    event.preventDefault();

    let new_subject = $("#event-subject").val();
    let new_start = $("#event-start").val();
    let new_category = $("#event-category").val();
    let new_end = $("#event-end").val();

    if (new_start=='') {
      if ($("#event-fields-error").hasClass("w3-show")===false) {
        $("#event-fields-error").addClass("w3-show");
      }
      $("#event-fields-error").text("A dátum hiányzik!");
      return;
    }

    let ds = new Date(new_start);
    let de = new Date(new_end);
    if (ds>de) {
      if ($("#event-fields-error").hasClass("w3-show")===false) {
        $("#event-fields-error").addClass("w3-show");
      }
      $("#event-fields-error").text("A kezdő dátum nem lehet nagyobb, mint a befejező dátum!");
      return;
    }

    $("#event-popup").toggleClass("w3-show");
    if (id) {
      updateEvent(id, new_subject, new_start, new_end, new_category);
    }
    else {
      newEvent(new_subject, new_start, new_end, new_category);
    }
    $("#confirm-text").text("");
  });
}

function newEvent(subject, start, end, category) {
  jx.data = {
    calendarClass: 'Event',
    filter: JSON.stringify({
      subject: subject,
      start: start,
      end: end,
      category: category
    }),
    _call: 'newEvent'
  };
  _do();
}

function updateEvent(id, subject, start, end, category) {
  jx.data = {
    calendarClass: 'Event',
    filter: JSON.stringify({
      id: id,
      subject: subject,
      start: start,
      end: end,
      category: category
    }),
    _call: 'updateEvent'
  };
  _do();
}

function removeEvent(id, n) {
  $("#confirm-text").text('Ezt fogod törölni: "'+n+'"!');
  $("#confirm").toggleClass("w3-show");
  $("#confirm-ok").off();

  $("#confirm-ok").click(function(event) {
    event.preventDefault();
    $("#confirm").toggleClass("w3-show");
    hideEvent(id);
    $("#confirm-text").text("");
  });
}

function hideEvent(id) {
  jx.data = {
    calendarClass: 'Event',
    filter: JSON.stringify({
      id: id
    }),
    _call: 'hideEvent'
  };
  _do();
}

function listEvents() {
  jx.data = {
    calendarClass: 'Event',
    filter: '*',
    _call: 'listEvents'
  };
  _do();
}
