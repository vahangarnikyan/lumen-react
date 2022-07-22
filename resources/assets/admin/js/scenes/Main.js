import React, { Component } from 'react';
import { Route, Switch } from 'react-router-dom';
import {
  connect
} from 'react-redux';
import { bindActionCreators } from 'redux';
import Layout from '../layouts/Full';
import Api from '../apis/app';
import { login } from '../actions/common';

import GeneralSettings from './Generals/Settings';
import GeneralUsers from './Generals/Users';
import GeneralUserDetail from './Generals/Users/Detail';
import GeneralUserAdd from './Generals/Users/Add';
import BlogsPosts from './Blogs/Posts';
import BlogPostAdd from './Blogs/Posts/Add';
import BlogPostDetail from './Blogs/Posts/Detail';
import AppearancesHome from './Appearances/Home';
import AppearancesFooter from './Appearances/Footer';
import ApperancesMenus from './Appearances/Menus';
import ChangePassword from './ChangePassword';

class Main extends Component {
  async componentDidMount() {
    const auth = Api.getAuth();
    if (auth && !this.props.auth) {
      await this.props.login(auth);
    }
  }

  render() {
    return (
      <Layout>
        <Switch>
          <Route path="/admin/generals/settings" name="Settings" component={GeneralSettings} />
          <Route path="/admin/generals/users/add" component={GeneralUserAdd} />
          <Route path="/admin/generals/users/:id(\d+)" name="User Detail" component={GeneralUserDetail} />
          <Route path="/admin/generals/users" name="Users" component={GeneralUsers} />
          
          <Route path="/admin/blogs/posts/add" name="BlogsPostAdd" component={BlogPostAdd} />
          <Route path="/admin/blogs/posts/:id(\d+)" name="BlogsPostDetail" component={BlogPostDetail} />
          <Route path="/admin/blogs/posts" name="BlogsPosts" component={BlogsPosts} />

          <Route path="/admin/appearances/home" name="Home" component={AppearancesHome} />
          <Route path="/admin/appearances/menus" name="Menus" component={ApperancesMenus} />
          <Route path="/admin/appearances/footer" name="Footer" component={AppearancesFooter} />

          <Route path="/admin/change-password" name="Change password" component={ChangePassword} />
        </Switch>
      </Layout>
    );
  }
}

const mapStateToProps = state => ({
  auth: state.common.auth
});

const mapDispatchToProps = dispatch => ({
  login: bindActionCreators(login, dispatch)
});

export default connect(mapStateToProps, mapDispatchToProps)(Main);
