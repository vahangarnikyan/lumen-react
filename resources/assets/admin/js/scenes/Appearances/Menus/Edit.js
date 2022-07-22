import React, { Component } from 'react';
import { Formik } from 'formik';
import * as Yup from 'yup';
import {
  Card, Form, FormLayout, InlineError,
  TextField, Button, ButtonGroup
} from '@shopify/polaris';
import Select from 'react-select';
import AsyncSelect from 'react-select/lib/Async';

import OptionsHelper from '../../../helpers/OptionsHelper';
import Api from '../../../apis/app';

class Edit extends Component {
  constructor(props) {
    super(props);
    this.formikRef = React.createRef();
    this.handleSubmit = this.handleSubmit.bind(this);
    this.handleReset = this.handleReset.bind(this);
    this.handleNew = this.handleNew.bind(this);

    this.handlePostInputChange = this.handlePostInputChange.bind(this);
    this.loadPostOptions = this.loadPostOptions.bind(this);
  }

  componentWillReceiveProps(props) {
    if (props.data) {
      const values = this.paramsToFormValues(props.data);
      this.formikRef.current.setValues(values);
      this.formikRef.current.setTouched({
        title: false,
        title_es: false,
        url: false,
        url_es: false,
        post: false
      });
    }
  }

  async getPostOptions(inputValue) {
    const {
      body
    } = await Api.get('admin/blogs/posts/search', {
      name: inputValue
    });
    return body;
  }

  formValuesToParams(values) {
    return {
      title: values.title,
      title_es: values.title_es,
      url: values.url,
      url_es: values.url_es,
      ...(values.post ? { post_id: values.post.id } : {})
    };
  }

  paramsToFormValues(params) {
    const values = {
      post: params.post,
      url: params.url,
      url_es: params.url_es,
      title: params.title,
      title_es: params.title_es
    };
    return values;
  }

  async loadPostOptions(inputValue, callback) {
    callback(await this.getPostOptions(inputValue));
  }

  handlePostInputChange(value) {
    return value;
  }

  async handleSubmit(values, bags) {
    const {
      onSubmit
    } = this.props;
    onSubmit(this.formValuesToParams(values), bags);
  }

  handleReset() {
    this.formikRef.current.setValues({
      url: '',
      title: ''
    });
  }

  handleNew() {
    const {
      onNew
    } = this.props;
    this.handleReset();
    onNew();
  }

  render() {
    const {
      isEditing
    } = this.props;

    return (
      <Formik
        ref={this.formikRef}
        initialValues={{
          post: null,
          url: '',
          url_es: '',
          title: '',
          title_es: ''
        }}
        validationSchema={
          Yup.object().shape({
            url: Yup.string().required(),
            title: Yup.string().required()
          })
        }
        onSubmit={this.handleSubmit}
        render={({
          values,
          errors,
          status,
          touched,
          setValues,
          setFieldValue,
          handleSubmit,
          handleBlur,
          isSubmitting
        }) => (
          <Card sectioned>
            <Form onSubmit={handleSubmit}>
              {status && <InlineError message={status} />}
              <FormLayout>
                <FormLayout.Group>
                  <AsyncSelect
                    placeholder="Post"
                    classNamePrefix="react-select"
                    cacheOptions
                    loadOptions={this.loadPostOptions}
                    value={values.post}
                    getOptionLabel={(value => (value.title))}
                    getOptionValue={(value => (value.id))}
                    onInputChange={this.handlePostInputChange}
                    onBlur={handleBlur}
                    onChange={async (value) => {
                      await setValues({
                        league: null,
                        sport_category: null,
                        post: value,
                        url: value.url,
                        url_es: value.url_es,
                        title: value.title,
                        title_es: value.title_es
                      });
                    }}
                  />
                  {(touched.post && errors.post) && <InlineError message={errors.post} />}
                </FormLayout.Group>
                <FormLayout.Group>
                  <TextField
                    placeholder="Title"
                    name="title"
                    value={values.title}
                    onBlur={handleBlur}
                    onChange={(value) => {
                      setFieldValue('title', value);
                    }}
                    error={touched.title && errors.title}
                  />
                  <TextField
                    disabled={!!(values.sport_category || values.league || values.post)}
                    placeholder="Url"
                    name="url"
                    value={values.url}
                    onBlur={handleBlur}
                    onChange={(value) => {
                      setFieldValue('url', value);
                    }}
                    error={touched.url && errors.url}
                  />
                </FormLayout.Group>
                <FormLayout.Group>
                  <TextField
                    placeholder="Title ES"
                    name="title_es"
                    value={values.title_es}
                    onBlur={handleBlur}
                    onChange={(value) => {
                      setFieldValue('title_es', value);
                    }}
                    error={touched.title_es && errors.title_es}
                  />
                  <TextField
                    disabled={!!(values.sport_category || values.league || values.post)}
                    placeholder="Url ES"
                    name="url_es"
                    value={values.url_es}
                    onBlur={handleBlur}
                    onChange={(value) => {
                      setFieldValue('url_es', value);
                    }}
                    error={touched.url_es && errors.url_es}
                  />
                </FormLayout.Group>
                <FormLayout.Group>
                  <ButtonGroup>
                    <Button
                      loading={isSubmitting}
                      submit
                      primary
                    >
                      {isEditing ? 'Update Menu' : 'Create Menu'}
                    </Button>
                    <Button
                      button
                      onClick={this.handleReset}
                      icon="circleCancel"
                    >
                      Reset
                    </Button>
                    <Button
                      disabled={!isEditing}
                      button
                      destructive
                      onClick={this.handleNew}
                      icon="circlePlus"
                    >
                      New
                    </Button>
                  </ButtonGroup>
                </FormLayout.Group>
              </FormLayout>
            </Form>
          </Card>
        )}
      />
    );
  }
}

Edit.defaultProps = {
  data: {},
  onSubmit: () => { },
  onNew: () => { }
};

export default Edit;
