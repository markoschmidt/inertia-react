import React, { useState } from "react";
import Helmet from "react-helmet";
import { InertiaLink, usePage, useRemember } from "@inertiajs/inertia-react";
import { BaseLayout as Layout } from "@/Components/Layouts";
import { TextInput } from "@/Components/Inputs";
import { LoadingButton } from "@/Components/Buttons";
import { toFormData } from "@/utils";
import Icon from "@/Components/Icon";
import { Inertia } from "@inertiajs/inertia";

export default () => {
  const { role, errors, locale } = usePage().props;
  const [sending, setSending] = useState(false);
  const [values, setValues] = useRemember({
    name: role.name,
  });

  function handleSubmit(e) {
    e.preventDefault();
    setSending(true);

    const formData = toFormData(values, "PUT");

    Inertia.post(route("roles.update", role.id), formData).then(() => {
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
        <Helmet title={`Role ${role.name}`} />
        <div className="flex justify-start max-w-lg mb-8">
          <h1 className="text-3xl font-bold">
            <InertiaLink
              href={route("roles.index")}
              className="text-indigo-600 hover:text-indigo-700"
            >
              Roles
            </InertiaLink>
            <span className="mx-2 font-medium text-indigo-600">/</span>
            {values.name}
          </h1>
        </div>
        <div className="max-w-3xl overflow-hidden bg-white rounded shadow">
          <form onSubmit={handleSubmit}>
            <div className="flex flex-wrap p-8 -mb-8 -mr-6">
              <TextInput
                className="w-full pb-8 pr-6 lg:w-1/2"
                label="Name"
                name="name"
                errors={errors.name}
                value={values.name}
                onChange={handleChange}
              />
            </div>
            <LoadingButton
              loading={sending}
              type="submit"
              className="ml-auto btn-indigo"
            >
              Update Role
            </LoadingButton>
          </form>
        </div>
        <h2 className="mt-4 mb-4 text-2xl font-bold">Permissions for {role.name}</h2>
        <div className="overflow-x-auto bg-white rounded shadow">
          <table className="w-full whitespace-no-wrap">
            <thead>
              <tr className="font-bold text-left">
                <th className="px-6 pt-5 pb-4" colSpan="2">
                  Name
                </th>
              </tr>
            </thead>
            <tbody>
              {role.permissions.map(({ id, name }) => (
                <tr
                  key={id}
                  className="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                  <td className="border-t">
                    <InertiaLink
                      href={route("permissions.edit", id)}
                      className="flex items-center px-6 py-4 focus:text-indigo"
                    >
                      {name}
                    </InertiaLink>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </Layout>
  );
};
