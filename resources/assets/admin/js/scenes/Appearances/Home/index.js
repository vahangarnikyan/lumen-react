/* eslint-disable no-shadow */
import React, { Component } from 'react';
// import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { withRouter } from 'react-router-dom';
import {
  Page, Card, Button,
  FormLayout,
  TextStyle
} from '@shopify/polaris';

import CKEditor from '@ckeditor/ckeditor5-react';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

import Api from '../../../apis/app';

class Home extends Component {
  constructor(props) {
    super(props);

    this.state = {
      id1: '',
      id2: '',
      content1: '',
      content1_es: '',
      content2: '',
      content2_es: '',
      msg: false
    }

    this.handleSave = this.handleSave.bind(this);
  }

  async componentDidMount() {
    let param1 = {search: 'home_page'};

    const data1 = await Api.get('admin/blogs/posts/search', param1);
    switch (data1.response.status) {
      case 200:
        if (data1.body.length > 0) {
          this.setState({
            id1: data1.body[0].id,
            content1: data1.body[0].content,
            content1_es: data1.body[0].content_es
          });
        }
        break;
      default:
        break;
    }

    let param2 = {search: 'home_part'};

    const data2 = await Api.get('admin/blogs/posts/search', param2);
    switch (data2.response.status) {
      case 200:
        if (data2.body.length > 0) {
          this.setState({
            id2: data2.body[0].id,
            content2: data2.body[0].content,
            content2_es: data2.body[0].content_es
          });
        }
        break;
      default:
        break;
    }
  }

  async handleSave() {
    const { 
      id1, id2,
      content1, content1_es,
      content2, content2_es
    } = this.state;
    
    let newData1 = {
      title: 'Homepage',
      title_es: 'Página Principal',
      slug: 'home_page',
      slug_es: 'página_principal',
      content: content1,
      content_es: content1_es,
      post_type: 'post',
      excerpt: '',
      excerpt_es: '',
      meta_keywords: 'homepage',
      meta_keywords_es: 'página_principal',
      meta_description: 'homepage',
      meta_description_es: 'página_principal'
    }

    if (id1 == '') {
      const { response, body } = await Api.post('admin/blogs/posts', newData1);
      switch (response.status) {
        case 200:
          this.setState({
            id1: body.id,
            msg: true
          });

          setTimeout(() => {
            this.setState({ msg: false });
          }, 2000);
          break;
        default:
          break;
      }
    } else {
      const { response, body } = await Api.put(`admin/blogs/posts/${id1}`, newData1);
      switch (response.status) {
        case 200:
          this.setState({
            id1: body.id,
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

    let newData2 = {
      title: 'Homepage',
      title_es: 'Página Principal',
      slug: 'home_part',
      slug_es: 'página_principal',
      content: content2,
      content_es: content2_es,
      post_type: 'post',
      excerpt: '',
      excerpt_es: '',
      meta_keywords: 'homepage',
      meta_keywords_es: 'página_principal',
      meta_description: 'homepage',
      meta_description_es: 'página_principal'
    }

    if (id2 == '') {
      const { response, body } = await Api.post('admin/blogs/posts', newData2);
      switch (response.status) {
        case 200:
          this.setState({
            id2: body.id,
            msg: true
          });

          setTimeout(() => {
            this.setState({ msg: false });
          }, 2000);
          break;
        default:
          break;
      }
    } else {
      const { response, body } = await Api.put(`admin/blogs/posts/${id2}`, newData2);
      switch (response.status) {
        case 200:
          this.setState({
            id2: body.id,
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
  }

  render() {
    const { 
      content1, content1_es,
      content2, content2_es,
      msg
    } = this.state;

    return (
      <Page
        title="Home Page"
        fullWidth
      >
        <Card sectioned>
          { msg && <h1 className="msg">Saved Successfully!</h1> }

          <FormLayout>
            <FormLayout.Group>
              <div>
                <TextStyle>Content1</TextStyle>
                <CKEditor
                  editor={ClassicEditor}
                  data={content1}
                  config={{
                    heading: {
                      options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        {
                          model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1'
                        },
                        {
                          model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2'
                        },
                        {
                          model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3'
                        },
                        {
                          model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4'
                        },
                        {
                          model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5'
                        },
                        {
                          model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6'
                        }
                      ]
                    }
                  }}
                  onChange={(event, editor) => {
                    this.setState({
                      content1: editor.getData()
                    });
                  }}
                />
              </div>
            </FormLayout.Group>
            <FormLayout.Group>
              <div>
                <TextStyle>Content1 ES</TextStyle>
                <CKEditor
                  editor={ClassicEditor}
                  data={content1_es}
                  config={{
                    heading: {
                      options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        {
                          model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1'
                        },
                        {
                          model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2'
                        },
                        {
                          model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3'
                        },
                        {
                          model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4'
                        },
                        {
                          model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5'
                        },
                        {
                          model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6'
                        }
                      ]
                    }
                  }}
                  onChange={(event, editor) => {
                    this.setState({
                      content1_es: editor.getData()
                    });
                  }}
                />
              </div>
            </FormLayout.Group>
            <FormLayout.Group>
              <div>
                <TextStyle>Content2</TextStyle>
                <CKEditor
                  editor={ClassicEditor}
                  data={content2}
                  config={{
                    heading: {
                      options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        {
                          model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1'
                        },
                        {
                          model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2'
                        },
                        {
                          model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3'
                        },
                        {
                          model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4'
                        },
                        {
                          model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5'
                        },
                        {
                          model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6'
                        }
                      ]
                    }
                  }}
                  onChange={(event, editor) => {
                    this.setState({
                      content2: editor.getData()
                    });
                  }}
                />
              </div>
            </FormLayout.Group>
            <FormLayout.Group>
              <div>
                <TextStyle>Content2 ES</TextStyle>
                <CKEditor
                  editor={ClassicEditor}
                  data={content2_es}
                  config={{
                    heading: {
                      options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        {
                          model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1'
                        },
                        {
                          model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2'
                        },
                        {
                          model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3'
                        },
                        {
                          model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4'
                        },
                        {
                          model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5'
                        },
                        {
                          model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6'
                        }
                      ]
                    }
                  }}
                  onChange={(event, editor) => {
                    this.setState({
                      content2_es: editor.getData()
                    });
                  }}
                />
              </div>
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

export default withRouter(connect(mapStateToProps, mapDispatchToProps)(Home));
