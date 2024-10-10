(function() {
  var $, CalendarController, ValidationController, padZero2, padZero4, timezoneOffset, _ref,
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  $ = jQuery;

  padZero2 = function(n) {
    if (n < 10) {
      return "0" + n;
    } else {
      return "" + n;
    }
  };

  padZero4 = function(n) {
    if (n < 10) {
      return "000" + n;
    } else if (n < 100) {
      return "00" + n;
    } else if (n < 1000) {
      return "0" + n;
    } else {
      return "" + n;
    }
  };

  window.MAIL_REGEX = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

  window.KEY_CODE = {
    tab: 9,
    shift: 16,
    arrow_left: 37,
    arrow_up: 38,
    arrow_right: 39,
    arrow_down: 40,
    space: 32,
    backspace: 8,
    enter: 13
  };

  $.fn.hud = (function() {
    var show;
    show = function(duration, speed_in, speed_out) {
      var $el, close, event_off, force_close, timeout;
      $el = $(this).trigger('_off.ocapi.hud');
      event_off = function() {
        return $el.off('click', close).off('close.ocapi.hud', force_close).off('_off.ocapi.hud', event_off);
      };
      timeout = setTimeout((function() {
        $el.fadeOut(speed_out);
        return event_off;
      }), duration);
      if (duration < 0) {
        clearTimeout(timeout);
      }
      close = function() {
        if (!$el.hasClass('closeable')) {
          return;
        }
        return force_close();
      };
      force_close = function() {
        clearTimeout(timeout);
        event_off;
        return $el.fadeOut(speed_out).trigger('hide.ocapi.hud');
      };
      return $el.fadeIn(speed_in).one('click', close).one('close.ocapi.hud', force_close).one('_off.ocapi.hud', event_off).trigger('show.ocapi.hud');
    };
    return function(duration, speed_in, speed_out) {
      return this.each(function() {
        return show.call(this, duration, speed_in, speed_out != null ? speed_out : speed_in);
      });
    };
  })();

  Number.prototype.commafy = function() {
    return String(this).replace(/(\d)(?=(\d\d\d)+$)/g, '$1,');
  };

  String.prototype.repeat = function(n) {
    var _i, _results;
    return _.map((function() {
      _results = [];
      for (var _i = 0; 0 <= n ? _i < n : _i > n; 0 <= n ? _i++ : _i--){ _results.push(_i); }
      return _results;
    }).apply(this), (function(_this) {
      return function() {
        return _this;
      };
    })(this)).join('');
  };

  Date.today = function() {
    var today;
    today = new Date();
    return new Date(today.getFullYear(), today.getMonth(), today.getDate());
  };

  Date.prototype.to_iso_localdate = function() {
    return "" + (padZero4(this.getFullYear())) + "-" + (padZero2(this.getMonth() + 1)) + "-" + (padZero2(this.getDate()));
  };

  Date.from_local_iso = function(val) {
    var match;
    val = String(val);
    match = val.match(/^(\d{4})-(\d{1,2})-(\d{1,2})(?:T(\d{1,2}):(\d{1,2})(?::\d{2}(?:\.\d{3})?)?)?$/);
    if (match == null) {
      return null;
    }
    return new Date(parseInt(match[1], 10), parseInt(match[2], 10) - 1, parseInt(match[3], 10), parseInt(match[4] || '0', 10), parseInt(match[5] || '0', 10));
  };

  Date.from_iso = function(val) {
    var d, match, timezone_offset;
    val = String(val);
    match = val.match(/^(\d{4})-(\d{1,2})-(\d{1,2})(?:T(\d{1,2}):(\d{1,2}):(\d{2})(?:\.(\d{3}))?(?:Z|([+-]\d{2}):(\d{2}))?)?$/);
    if (match == null) {
      return null;
    }
    d = new Date(parseInt(match[1], 10), parseInt(match[2], 10) - 1, parseInt(match[3], 10), parseInt(match[4], 10) || 0, parseInt(match[5], 10) || 0, parseInt(match[6], 10) || 0, parseInt(match[7], 10) || 0);
    timezone_offset = parseInt(match[8], 10) * 60 + (parseInt(match[9], 10) || 0);
    if (_.isNaN(timezone_offset)) {
      timezone_offset = 0;
    }
    return new Date(d.getTime() - (timezone_offset + d.getTimezoneOffset()) * 60 * 1000);
  };

  Date.from_utc = function(val) {
    var d, match;
    val = String(val);
    match = val.match(/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})/);
    if (match == null) {
      return null;
    }
    d = new Date(0);
    d.setUTCFullYear(parseInt(match[1], 10));
    d.setUTCMonth(parseInt(match[2], 10) - 1);
    d.setUTCDate(parseInt(match[3], 10));
    d.setUTCHours(parseInt(match[4], 10));
    d.setUTCMinutes(parseInt(match[5], 10));
    return d;
  };

  timezoneOffset = function(n, colon) {
    var abs;
    abs = Math.abs(n);
    return "" + (n < 0 ? '-' : '+') + (padZero2(Math.floor(abs / 60))) + (colon ? ':' : '') + (padZero2(Math.floor(abs % 60)));
  };

  Date.prototype.to_iso = (function() {
    return function() {
      return "" + (padZero4(this.getFullYear())) + "-" + (padZero2(this.getMonth() + 1)) + "-" + (padZero2(this.getDate())) + "T" + (padZero2(this.getHours())) + ":" + (padZero2(this.getMinutes())) + ":" + (padZero2(this.getSeconds())) + (timezoneOffset(-this.getTimezoneOffset(), true));
    };
  })();

  Date.prototype.strftime = (function() {
    var fn, full_month, short_month;
    short_month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    full_month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    fn = {
      '%Y': function(d) {
        return padZero4(d.getFullYear());
      },
      '%-Y': function(d) {
        return d.getFullYear();
      },
      '%m': function(d) {
        return padZero2(d.getMonth() + 1);
      },
      '%-m': function(d) {
        return d.getMonth() + 1;
      },
      '%d': function(d) {
        return padZero2(d.getDate());
      },
      '%-d': function(d) {
        return d.getDate();
      },
      '%H': function(d) {
        return padZero2(d.getHours());
      },
      '%-H': function(d) {
        return d.getHours();
      },
      '%M': function(d) {
        return padZero2(d.getMinutes());
      },
      '%-M': function(d) {
        return d.getMinutes();
      },
      '%S': function(d) {
        return padZero2(d.getSeconds());
      },
      '%-S': function(d) {
        return d.getSeconds();
      },
      '%b': function(d) {
        return short_month[d.getMonth()];
      },
      '%^b': function(d) {
        return short_month[d.getMonth()].toUpperCase();
      },
      '%B': function(d) {
        return full_month[d.getMonth()];
      },
      '%^B': function(d) {
        return full_month[d.getMonth()].toUpperCase();
      },
      '%z': function(d) {
        return timezoneOffset(-d.getTimezoneOffset(), false);
      },
      '%:z': function(d) {
        return timezoneOffset(-d.getTimezoneOffset(), true);
      },
      '%::z': function(d) {
        return "" + (timezoneOffset(-d.getTimezoneOffset(), true)) + ":00";
      }
    };
    return function(format) {
      var d;
      d = this;
      return format.replace(/%-?Y|%-?m|%-?d|%-?H|%-?M|%-?S|%\^?b|%\^?B|%:{0,2}z/g, function(m) {
        return fn[m](d);
      });
    };
  })();

  String.prototype.to_half = (function() {
    var chars, pattern;
    chars = {
      '　': ' ',
      '！': '!',
      '＂': '"',
      '＃': '#',
      '＄': '$',
      '％': '%',
      '＆': '&',
      '＇': '\'',
      '（': '(',
      '）': ')',
      '＊': '*',
      '＋': '+',
      '，': ',',
      '－': '-',
      '．': '.',
      '／': '/',
      '０': '0',
      '１': '1',
      '２': '2',
      '３': '3',
      '４': '4',
      '５': '5',
      '６': '6',
      '７': '7',
      '８': '8',
      '９': '9',
      '：': ':',
      '；': ';',
      '＜': '<',
      '＝': '=',
      '＞': '>',
      '？': '?',
      '＠': '@',
      'Ａ': 'A',
      'Ｂ': 'B',
      'Ｃ': 'C',
      'Ｄ': 'D',
      'Ｅ': 'E',
      'Ｆ': 'F',
      'Ｇ': 'G',
      'Ｈ': 'H',
      'Ｉ': 'I',
      'Ｊ': 'J',
      'Ｋ': 'K',
      'Ｌ': 'L',
      'Ｍ': 'M',
      'Ｎ': 'N',
      'Ｏ': 'O',
      'Ｐ': 'P',
      'Ｑ': 'Q',
      'Ｒ': 'R',
      'Ｓ': 'S',
      'Ｔ': 'T',
      'Ｕ': 'U',
      'Ｖ': 'V',
      'Ｗ': 'W',
      'Ｘ': 'X',
      'Ｙ': 'Y',
      'Ｚ': 'Z',
      '［': '[',
      '＼': '\\',
      '］': ']',
      '＾': '^',
      '＿': '_',
      '｀': '`',
      'ａ': 'a',
      'ｂ': 'b',
      'ｃ': 'c',
      'ｄ': 'd',
      'ｅ': 'e',
      'ｆ': 'f',
      'ｇ': 'g',
      'ｈ': 'h',
      'ｉ': 'i',
      'ｊ': 'j',
      'ｋ': 'k',
      'ｌ': 'l',
      'ｍ': 'm',
      'ｎ': 'n',
      'ｏ': 'o',
      'ｐ': 'p',
      'ｑ': 'q',
      'ｒ': 'r',
      'ｓ': 's',
      'ｔ': 't',
      'ｕ': 'u',
      'ｖ': 'v',
      'ｗ': 'w',
      'ｘ': 'x',
      'ｙ': 'y',
      'ｚ': 'z',
      '｛': '{',
      '｜': '|',
      '｝': '}',
      '～': '~'
    };
    pattern = new RegExp("[" + (_.keys(chars).join('')) + "]", 'g');
    return function() {
      return this.replace(pattern, function(m) {
        return chars[m];
      });
    };
  })();

  String.prototype.to_half_for_team = (function() {
    var half_pattern, replace_to_half;
    half_pattern = /[\uff01-\uff5d\uff5f\uff60\uffe0-\uffe6\u3000]/g;
    replace_to_half = function(m) {
      switch (m) {
        case '\u3000':
          return ' ';
        case '\uff5f':
          return '\u2985';
        case '\uff60':
          return '\u2986';
        case '\uffe0':
          return '\u00a2';
        case '\uffe1':
          return '\u00a3';
        case '\uffe2':
          return '\u00ac';
        case '\uffe3':
          return '\u203e';
        case '\uffe4':
          return '\u00a6';
        case '\uffe5':
          return '\\';
        case '\uffe6':
          return '\u20a9';
        default:
          return String.fromCharCode(m.charCodeAt(0) - 0xfee0);
      }
    };
    return function() {
      return this.replace(half_pattern, replace_to_half);
    };
  })();

  _ref = (function() {
    var parser;
    parser = function(tokenizer, delim) {
      return function(text) {
        var data, field, record;
        if (text.length === 0) {
          return [];
        }
        data = [['']];
        record = 0;
        field = 0;
        text.replace(/\r/g, '').replace(/\n+$/, '').replace(tokenizer, function(tok) {
          switch (tok) {
            case delim:
              return data[record][++field] = '';
            case '\n':
              data[++record] = [''];
              return field = 0;
            default:
              if (tok.charAt(0) === '"') {
                tok = tok.slice(1, -1).replace(/""/g, '"');
              }
              return data[record][field] = tok;
          }
        });
        return data;
      };
    };
    return [parser(/,|\n|[^,"\n]+|"(?:[^"]|"")*"/g, ','), parser(/\t|\r?\n|[^"\t\r\n][^\t\r\n]*|"(?:[^"]|"")*"/g, '\t')];
  })(), window.parse_csv = _ref[0], window.parse_tsv = _ref[1];

  CalendarController = (function() {
    CalendarController.prototype.move = function(d) {
      var index, selectable, selected, val;
      selected = this.view.find('.selected')[0];
      val = selected ? (selectable = this.view.find('.selectable'), index = _.indexOf(selectable, selected) + d, index >= 0 ? selectable.eq(index) : $()) : this.view.find('.selectable:first');
      if (val.length > 0) {
        return this.value.val(val.data('date')).change();
      }
    };

    function CalendarController(view) {
      this.move = __bind(this.move, this);
      var $row, init_value, len, month_format, start, start_date, tmpl, today, year, _i, _ref1, _ref2, _results;
      this.view = $(view);
      this.value = this.view.find(':input:first');
      init_value = (_ref1 = Date.from_utc(this.value.data('initial-value-utc'))) != null ? _ref1.to_iso_localdate() : void 0;
      start_date = Date.from_iso(this.view.data('start-year'));
      start_date.setHours(0);
      start_date.setMinutes(0);
      start_date.setSeconds(0);
      start_date.setMilliseconds(0);
      start = start_date.getMonth() + 1;
      year = start_date.getFullYear();
      len = parseInt(this.view.data('length'), 10);
      today = Date.today();
      tmpl = _.template($('#calendar-template').html());
      month_format = $('#calendar-template').data('month-format');
      $row = $('<div class="row"></div>');
      _.chain((function() {
        _results = [];
        for (var _i = start, _ref2 = start + len; start <= _ref2 ? _i < _ref2 : _i > _ref2; start <= _ref2 ? _i++ : _i--){ _results.push(_i); }
        return _results;
      }).apply(this)).map(function(month) {
        return new Date(year, month - 1, 1);
      }).each(function(date) {
        var days, first_week_day, html, last_date, weeks, _i, _j, _k, _l, _ref2, _ref3, _ref4, _results, _results1, _results2, _results3;
        first_week_day = date.getDay();
        last_date = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        days = _.map((function() {
          _results2 = [];
          for (var _k = 0; 0 <= first_week_day ? _k < first_week_day : _k > first_week_day; 0 <= first_week_day ? _k++ : _k--){ _results2.push(_k); }
          return _results2;
        }).apply(this), function() {
          return '<td class="empty"></td>';
        }).concat(_.map((function() {
          _results1 = [];
          for (var _j = 1, _ref3 = last_date.getDate(); 1 <= _ref3 ? _j <= _ref3 : _j >= _ref3; 1 <= _ref3 ? _j++ : _j--){ _results1.push(_j); }
          return _results1;
        }).apply(this), function(n) {
          var classes, d;
          classes = ['day'];
          d = new Date(date.getFullYear(), date.getMonth(), n);
          if (d.getDay() === 0) {
            classes.push('sunday');
          }
          if (d.getDay() === 6) {
            classes.push('saturday');
          }
          if (d < today) {
            classes.push('disable');
          }
          if (d >= today) {
            classes.push('selectable');
          }
          if (d.getTime() === today.getTime()) {
            classes.push('today');
          }
          return "<td class=\"" + (classes.join(' ')) + "\" data-date=\"" + (d.to_iso_localdate()) + "\"><div>" + n + "</div></td>";
        })).concat(_.map((function() {
          _results = [];
          for (var _i = 0, _ref2 = 6 - last_date.getDay(); 0 <= _ref2 ? _i < _ref2 : _i > _ref2; 0 <= _ref2 ? _i++ : _i--){ _results.push(_i); }
          return _results;
        }).apply(this), function() {
          return '<td class="empty"></td>';
        }));
        weeks = _.map((function() {
          _results3 = [];
          for (var _l = 0, _ref4 = days.length / 7; 0 <= _ref4 ? _l < _ref4 : _l > _ref4; 0 <= _ref4 ? _l++ : _l--){ _results3.push(_l); }
          return _results3;
        }).apply(this), function(n) {
          return "<tr>" + (days.slice(n * 7, n * 7 + 7).join('')) + "</tr>";
        }).join('');
        html = tmpl({
          weeks: weeks,
          first_week_day: first_week_day,
          first_week_days: 7 - first_week_day,
          month: date.strftime(month_format)
        });
        return $row.append(html);
      });
      $row.appendTo(view);
      $row.on('mouseenter', 'td.selectable', function() {
        return $(this).addClass('hover');
      });
      $row.on('mouseleave', 'td.selectable', function() {
        return $(this).removeClass('hover');
      });
      this.view.on('click', 'td.selectable', (function(_this) {
        return function(e) {
          return _this.value.val($(e.currentTarget).data('date')).blur().change();
        };
      })(this));
      this.value.on('change', (function(_this) {
        return function() {
          var d, selected_date, selected_time, start_time, _ref3;
          d = ((_ref3 = Date.from_local_iso(_this.value.val())) != null ? _ref3.to_iso_localdate() : void 0) || '';
          _this.view.find('.selected').removeClass('selected');
          _this.view.find('.hover').removeClass('hover');
          _this.view.find('.selectable').filter("[data-date=\"" + d + "\"]").addClass('selected');
          selected_date = Date.from_iso(_this.view.find('.selected').data('date'));
          if (selected_date != null) {
            selected_time = selected_date.getTime();
            start_time = start_date.getTime();
            return _this.view.find('.day').each(function() {
              var $e, time;
              $e = $(this);
              time = Date.from_iso($e.data('date')).getTime();
              return $e.toggleClass('in_term', time <= selected_time && time > start_time);
            });
          } else {
            return _this.view.find('.in_term').removeClass('in_term');
          }
        };
      })(this));
      this.value.attr('min', today.to_iso_localdate());
      this.value.attr('max', new Date(year, start + len - 1, 0).to_iso_localdate());
      if (init_value) {
        this.value.attr('min', [init_value, this.value.attr('min')].sort()[0]);
        this.value.attr('max', [init_value, this.value.attr('max')].sort()[1]);
        this.value.val(init_value);
        this.view.find("[data-date=\"" + init_value + "\"]").addClass('selectable').removeClass('disable');
        this.value.data('accept', init_value);
      }
      this.value.on('focus', (function(_this) {
        return function() {
          return _this.view.addClass('focus');
        };
      })(this));
      this.value.on('blur', (function(_this) {
        return function() {
          return _this.view.removeClass('focus');
        };
      })(this));
      $('body').on('click', (function(_this) {
        return function(e) {
          var $view;
          $view = $(e.target).closest(_this.view);
          if ($view.length === 0 && _this.view.hasClass('focus')) {
            _this.value.blur();
          }
          if ($view.length > 0) {
            return _this.view.addClass('focus');
          }
        };
      })(this));
      $('body').on('focus', ':input, a', (function(_this) {
        return function(e) {
          if (e.currentTarget === _this.value[0]) {
            return;
          }
          if (_this.view.hasClass('focus')) {
            return _this.value.blur();
          }
        };
      })(this));
      $(window).on('keydown', (function(_this) {
        return function(e) {
          var type, val;
          if (!_this.view.hasClass('focus')) {
            return;
          }
          switch (e.which) {
            case KEY_CODE.tab:
            case KEY_CODE.enter:
            case KEY_CODE.space:
            case KEY_CODE.backspace:
              _this.value.focus();
              type = _this.value.attr('type');
              _this.value.attr('type', 'text');
              _.defer(function() {
                return _this.value.attr('type', type);
              });
              break;
            case KEY_CODE.arrow_up:
              _this.move(-7);
              return e.preventDefault();
            case KEY_CODE.arrow_down:
              _this.move(7);
              return e.preventDefault();
            case KEY_CODE.arrow_left:
              _this.move(-1);
              return e.preventDefault();
            case KEY_CODE.arrow_right:
              _this.move(1);
              return e.preventDefault();
            default:
              val = _this.value.val();
              return _.defer(function() {
                return _this.value.val(val).change();
              });
          }
        };
      })(this));
      this.value.change();
    }

    return CalendarController;

  })();

  window.CalendarController = CalendarController;

  ValidationController = (function() {
    ValidationController.prototype.require = function() {
      if (!this.input.val()) {
        return this.input.data('required') || this.input.data('validation-error');
      }
    };

    ValidationController.prototype.mail = function() {
      var val;
      val = this.input.val();
      if (!val) {
        return;
      }
      this.input.val(val.to_half());
      if (!this.input.val().match(MAIL_REGEX)) {
        return this.input.data('validation-error');
      }
    };

    ValidationController.prototype.number = function() {
      var val;
      val = this.input.val();
      if (!val) {
        return;
      }
      this.input.val(val.to_half());
      val = parseInt(this.input.val(), 10);
      if (!this.input.val().match(/^\d+$/) || val < parseInt(this.input.attr('min'), 10) || val > parseInt(this.input.attr('max'), 10)) {
        return this.input.data('validation-error');
      }
    };

    ValidationController.prototype.password = function() {
      var kind, uniq, val, _i, _ref1, _results;
      val = this.input.val();
      if (!val) {
        return;
      }
      uniq = _.uniq(_.map((function() {
        _results = [];
        for (var _i = 0, _ref1 = val.length; 0 <= _ref1 ? _i < _ref1 : _i > _ref1; 0 <= _ref1 ? _i++ : _i--){ _results.push(_i); }
        return _results;
      }).apply(this), function(e) {
        return val.charAt(e);
      })).length;
      kind = (val.match(/[a-z]/) != null) + (val.match(/[A-Z]/) != null) + (val.match(/[0-9]/) != null) + (val.match(/[^a-zA-Z0-9]/) != null);
      if (uniq < 4 || kind < 2 || val.length < 8) {
        return this.input.data('validation-error');
      }
    };

    ValidationController.prototype.credit_card = function() {
      var val;
      val = this.input.val();
      if (!val) {
        return;
      }
      this.input.val($.trim(val.to_half().replace(/\s/g, '').replace(/(\d{4})/g, '$1 ')));
      if (!this.input.val().match(/^\d{4} \d{4} \d{4} \d{4}$/)) {
        return this.input.data('validation-error');
      }
    };

    ValidationController.prototype.cvc = function() {
      var val;
      val = this.input.val();
      if (!val) {
        return;
      }
      this.input.val(val.to_half().replace(/\s/g, ''));
      if (!this.input.val().match(/^\d{3,4}$/)) {
        return this.input.data('validation-error');
      }
    };

    ValidationController.prototype.credit_card_name = function() {
      var val;
      val = this.input.val();
      if (!val) {
        return;
      }
      this.input.val(val.to_half().replace(/\s+/g, ' '));
      if (!this.input.val().match(/^[a-zA-Z ]+$/)) {
        return this.input.data('validation-error');
      }
    };

    ValidationController.prototype.postal = function() {
      var val;
      val = this.input.val();
      if (!val) {
        return;
      }
      val = val.to_half();
      if (val.match(/^\d{3}-?\d{4}$/)) {
        val = val.replace(/\D/g, '');
        this.input.val("" + (val.substring(0, 3)) + "-" + (val.substring(3)));
      }
      if (!this.input.val().match(/^\d{3}-\d{4}$/)) {
        return this.input.data('validation-error');
      }
    };

    ValidationController.prototype.date = function() {
      var accept, max, min, val;
      val = this.input.val();
      if (!val) {
        return;
      }
      val = Date.from_local_iso(val);
      if (val) {
        this.input.val(val.to_iso_localdate());
      }
      min = Date.from_local_iso(this.input.attr('min'));
      max = Date.from_local_iso(this.input.attr('max'));
      accept = Date.from_local_iso(this.input.data('accept'));
      if (!val || val - accept !== 0 && (val < min || val > max)) {
        // return this.input.data('validation-error');
      }
    };

    ValidationController.prototype.maxlength = function() {
      var max, val;
      val = this.input.val();
      if (!val) {
        return;
      }
      max = parseInt(this.input.attr('maxlength'), 10) || 0;
      if (val.length > max) {
        return this.input.data('validation-error');
      }
    };

    function ValidationController(input) {
      this.maxlength = __bind(this.maxlength, this);
      this.date = __bind(this.date, this);
      this.postal = __bind(this.postal, this);
      this.credit_card_name = __bind(this.credit_card_name, this);
      this.cvc = __bind(this.cvc, this);
      this.credit_card = __bind(this.credit_card, this);
      this.password = __bind(this.password, this);
      this.number = __bind(this.number, this);
      this.mail = __bind(this.mail, this);
      this.require = __bind(this.require, this);
      this.input = $(input);
      this.view = this.input.closest('.form-group');
      this.pop = this.input.is('.form-control') ? this.input : this.input.closest('.form-control, .form-control-static');
      this.fn = [];
      if (this.input.hasClass('required')) {
        this.fn.push(this.require);
      }
      if (this.input.hasClass('validation-number')) {
        this.fn.push(this.number);
      }
      if (this.input.hasClass('validation-mail')) {
        this.fn.push(this.mail);
      }
      if (this.input.hasClass('validation-password')) {
        this.fn.push(this.password);
      }
      if (this.input.hasClass('validation-date')) {
        this.fn.push(this.date);
      }
      if (this.input.hasClass('validation-cvc')) {
        this.fn.push(this.cvc);
      }
      if (this.input.hasClass('validation-credit-card-name')) {
        this.fn.push(this.credit_card_name);
      }
      if (this.input.hasClass('validation-credit-card')) {
        this.fn.push(this.credit_card);
      }
      if (this.input.hasClass('validation-maxlength')) {
        this.fn.push(this.maxlength);
      }
      if (this.input.hasClass('validation-postal')) {
        this.fn.push(this.postal);
      }
      this.input.on('focus', (function(_this) {
        return function() {
          return _this.pop.popover('show');
        };
      })(this));
      this.input.on('blur', (function(_this) {
        return function() {
          var error;
          _this.pop.popover('dispose');
          _this.input.val($.trim(_this.input.val()));
          error = _this.input.attr('disabled') ? [] : _.compact(_.map(_this.fn, function(e) {
            return e();
          }));
          _this.view.toggleClass('has-error', error.length > 0);
          if (error.length === 0) {
            return;
          }
          return _this.pop.popover({
            content: error[0],
            placement: _this.input.data('placement') || 'bottom',
            trigger: 'manual'
          });
        };
      })(this));
      if (this.input.hasClass('validation-credit-card') || this.input.hasClass('validation-cvc')) {
        this.input.on('focus', (function(_this) {
          return function() {
            var _ref1;
            _this.input.attr('pattern', '[0-9]*');
            return _this.input.val((_ref1 = _this.input.val()) != null ? _ref1.replace(/\s/g, '') : void 0);
          };
        })(this));
        this.input.on('blur', (function(_this) {
          return function() {
            return _this.input.removeAttr('pattern');
          };
        })(this));
      }
    }

    return ValidationController;

  })();

  window.ValidationController = ValidationController;

  $(function() {
    $('.validation').each(function() {
      return new ValidationController(this);
    });
    $('.btn.select select').on('change', function() {
      var $e, _ref1;
      $e = $(this);
      return $e.siblings('.value').text((_ref1 = $e.find(':selected').text()) != null ? _ref1 : '');
    }).change();
    $('.fn-copy-source').each(function() {
      var $e;
      $e = $(this);
      return $($e.data('dest')).html($e.html());
    });
    return (function() {
      $('#header').on('submit', 'form.ticket-form', function(e) {
        var $code, $el;
        e.preventDefault();
        $el = $(this);
        $code = $el.find('[name="code"]');
        $code.closest('.form-group').toggleClass('has-error', !$code.val());
        if ($el.find('.has-error').length > 0) {
          $el.find('.has-error:first').find(':input:first').focus();
          return;
        }
        $el.find('button').find('i').toggleClass('hidden').end().attr('disabled', true);
        return $.post("/use_ticket/" + ($code.val())).fail(function() {
          return $('.hud.ticket').children().addClass('hidden').end().find('.other').removeClass('hidden').end().hud(-1);
        }).done(function(result) {
          var $hud;
          $hud = $('.hud.ticket');
          $hud.children().addClass('hidden');
          $hud.find("." + result.code).removeClass('hidden');
          if (result.code === 'ok') {
            $hud.hud(5000);
            $('#ticket_count').text(result.count);
            return $('.fn-header-ticket').data('expired', result.expired);
          } else {
            return $hud.hud(-1);
          }
        }).always(function() {
          return $el.find('button').find('i').toggleClass('hidden').end().attr('disabled', false);
        });
      });
      return $('.fn-header-ticket').on('click', function(e) {
        var $el, close_popover, _ref1;
        e.preventDefault();
        $el = $(this);
        if ($el.hasClass('show-popover')) {
          return;
        }
        $el.parent().addClass('active');
        close_popover = function(e) {
          if ($(e.target).closest('#header .popover').length > 0) {
            return;
          }
          if ($(e.target).closest('.hud').length > 0) {
            return;
          }
          $el.popover('dispose').removeClass('show-popover');
          e.stopImmediatePropagation();
          return e.preventDefault();
        };
        return $el.popover({
          html: true,
          title: parseInt($('#ticket_count').text(), 10) > 0 ? (_ref1 = Date.from_iso($el.data('expired'))) != null ? _ref1.strftime($el.data('format')) : void 0 : void 0,
          placement: 'bottom',
          container: '#header',
          trigger: 'mamual',
          content: _.template($('#ticket').html() || '')()
        }).on('shown.bs.popover', function() {
          return _.defer(function() {
            return $('body').on('click', close_popover);
          });
        }).on('hide.bs.popover', function() {
          $('body').off('click', close_popover);
          return $el.parent().removeClass('active');
        }).popover('show').addClass('show-popover');
      });
    })();
  });
}).call(this);