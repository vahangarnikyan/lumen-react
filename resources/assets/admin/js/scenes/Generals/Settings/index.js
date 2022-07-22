import React, { Component } from 'react';
import {
  Page
} from '@shopify/polaris';

import AppSettings from './App';
import SocialMediaSettings from './SocialMedia';
import BannerSettings from './Banner';

class Index extends Component {
  render() {
    return (
      <Page
        title="Settings"
        fullWidth
      >
        <AppSettings />
        <SocialMediaSettings />
        <BannerSettings />
      </Page>
    );
  }
}

export default Index;
