import React, { useState } from "react";
import Helmet from "react-helmet";
import { InertiaLink, usePage } from "@inertiajs/inertia-react";
import { BaseLayout as Layout } from "@/Components/Layouts";
import { TextInput } from "@/Components/Inputs";
import { LoadingButton } from "@/Components/Buttons";
import { toFormData } from "@/utils";
import { Inertia } from "@inertiajs/inertia";

export default () => {
  const { category, errors } = usePage().props;
  const [sending, setSending] = useState(false);
  const [values, setValues] = useState({
    title: category.title,
  });

  function handleSubmit(e) {
    e.preventDefault();
    setSending(true);

    const formData = toFormData(values, "PUT");

    Inertia.post(route("categories.update", category.id), formData).then(() => {
      setSending(false);
    });
  }

  function handleChange(e) {
    const key = e.target.name;
    const value = e.target.value;

    setValues((values) => ({
      ...values,
      [key]: value,
    }));
  }

  return (
    <Layout>
      <div>
        <Helmet title={`Category ${category.title}`} />
        <div className="flex justify-start max-w-lg mb-8">
          <h1 className="text-3xl font-bold">
            <InertiaLink
              href={route("categories.index")}
              className="text-indigo-600 hover:text-indigo-700"
            >
              Categories
            </InertiaLink>
            <span className="mx-2 font-medium text-indigo-600">/</span>
            {values.title}
          </h1>
        </div>
        <div className="max-w-3xl overflow-hidden bg-white rounded shadow">
          <form onSubmit={handleSubmit}>
            <div className="flex flex-wrap p-8 -mb-8 -mr-6">
              <TextInput
                className="w-full pb-8 pr-6 lg:w-1/2"
                label="Title"
                name="title"
                errors={errors.title}
                value={values.title}
                onChange={handleChange}
              />
            </div>
            <LoadingButton
              loading={sending}
              type="submit"
              className="ml-auto btn-indigo"
            >
              Update Category
            </LoadingButton>
          </form>
        </div>
      </div>
    </Layout>
  );
};
