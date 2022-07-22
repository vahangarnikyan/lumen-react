/* eslint-disable no-undef */
import moment from 'moment';
import QueryString from 'qs';

class Common {
  constructor() {
    this.initSelect2();
  }

  initSelect2() {
    $('.select2').select2({
      theme: 'bootstrap4'
    });
  }
}

export default Common;
