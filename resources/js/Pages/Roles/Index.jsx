import React, { useContext } from "react";
import Helmet from "react-helmet";
import { InertiaLink, usePage } from "@inertiajs/inertia-react";
import { BaseLayout as Layout } from "@/Components/Layouts";
import { MainContext } from "@/Contexts/MainContext";

export default () => {
  const { roles } = usePage().props;
  const { data, links } = roles;
  const { locale } = useContext(MainContext);

  return (
    <Layout>
      <div>
        <Helmet title="Roles" />
        <h1 className="mb-8 text-3xl font-bold">Roles</h1>
        <div className="overflow-x-auto bg-white shadow roudned">
          <table className="w-full whitespace-no-wrap">
            <thead>
              <tr className="font-bold text-left">
                <th className="px-6 pt-5 pb-4">Name</th>
              </tr>
            </thead>
            <tbody>
              {data.map(({ id, name }) => (
                <tr
                  key={id}
                  className="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                  <td className="border-t">
                    <InertiaLink
                      href={route("roles.edit", id)}
                      className="flex items-center px-6 py-4 focus:text-indigo-700"
                    >
                      {name[locale]}
                    </InertiaLink>
                  </td>
                </tr>
              ))}
              {data.length === 0 && (
                <tr>
                  <td className="px-6 py-4 border-t" colSpan="4">
                    No roles found.
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </Layout>
  );
};
