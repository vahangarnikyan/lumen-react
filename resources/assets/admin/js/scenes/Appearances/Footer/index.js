/* eslint-disable no-shadow */
import React, { Component } from 'react';
// import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { withRouter } from 'react-router-dom';
import {
  Page, Card, Button,
  FormLayout,
  TextField
} from '@shopify/polaris';

import Api from '../../../apis/app';

class Footer extends Component {
  constructor(props) {
    super(props);

    this.state = {
      text: '',
      msg: false
    }

    this.handleSave = this.handleSave.bind(this);
  }

  async componentDidMount() {
    const data = await Api.get('admin/appearances/footer');
    const { response, body } = data;
    switch (response.status) {
      case 200:
        if (body.length > 0) {
          this.setState({
            text: body[0].value
          });
        }
        break;
      default:
        break;
    }
  }

  async handleSave() {
    const { text } = this.state;
    
    const data = await Api.post('admin/appearances/footer', {value: text});
    const { response } = data;
    switch (response.status) {
      case 200:
        this.setState({
          msg: true
        });

        setTimeout(() => {
          this.setState({ msg: false });
        }, 2000);
        break;
      default:
        break;
    }
  }

  render() {
    const { text, msg } = this.state;

    return (
      <Page
        title="Footer"
        fullWidth
      >
        <Card sectioned>
          { msg && <h1 className="msg">Saved Successfully!</h1> }

          <FormLayout>
            <FormLayout.Group>
              <TextField
                label="Footer Text"
                type="text"
                value={text}
                onChange={(value) => {
                  this.setState({ text: value });
                }}
              />
            </FormLayout.Group>
            <FormLayout.Group>
              <Button
                primary
                onClick={this.handleSave}
              >
                Save
              </Button>
            </FormLayout.Group>
          </FormLayout>
        </Card>
      </Page>
    );
  }
}


const mapStateToProps = state => ({
  auth: state.common.auth
});

const mapDispatchToProps = () /* dispatch */ => ({
  // logout: bindActionCreators(logout, dispatch)
});

export default withRouter(connect(mapStateToProps, mapDispatchToProps)(Footer));
