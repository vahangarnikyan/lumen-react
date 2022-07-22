/* eslint-disable no-unused-vars */
/* eslint-disable no-undef */
import 'babel-polyfill';
import 'dom4';
import 'whatwg-fetch';
import 'slick-carousel';
import 'select2';
import '@webcomponents/custom-elements/src/native-shim';
import '@webcomponents/custom-elements/src/custom-elements';
import 'lightpick';
import moment from 'moment';

import Navigation from './components/navigation';

import Common from './pages/common';

moment.locale('en');
window.customElements.define('app-navigation', Navigation);

const common = new Common();