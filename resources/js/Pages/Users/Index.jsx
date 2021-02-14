import React, { useContext } from "react";
import Helmet from "react-helmet";
import { InertiaLink, usePage } from "@inertiajs/inertia-react";
import { BaseLayout as Layout } from "@/Components/Layouts";
import Icon from "@/Components/Icon";
import Pagination from "@/Components/Pagination/Pagination";
import { MainContext } from "@/Contexts/MainContext"

export default () => {
  const { users } = usePage().props;
  const { data, links } = users;
  const { locale } = useContext(MainContext);

  return (
    <Layout>
      <div>
        <Helmet title="Users" />
        <h1 className="mb-8 text-3xl font-bold">Users</h1>
        <div className="overflow-x-auto bg-white rounded shadow">
          <table className="w-full whitespace-no-wrap">
            <thead>
              <tr className="font-bold text-left">
                <th className="px-6 pt-5 pb-4">Name</th>
                <th className="px-6 pt-5 pb-4">Email</th>
                <th className="px-6 pt-5 pb-4">Role</th>
              </tr>
            </thead>
            <tbody>
              {data.map(({ id, name, email, roles }) => (
                <tr
                  key={id}
                  className="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                  <td className="border-t">
                    <InertiaLink
                      href={route("users.edit", id)}
                      className="flex items-center px-6 py-4 focus:text-indigo-700"
                      title={`Edit user ${name}`}
                    >
                      {name}
                    </InertiaLink>
                  </td>
                  <td className="border-t">
                    <InertiaLink
                      tabIndex="-1"
                      href={route("users.edit", id)}
                      className="flex items-center px-6 py-4 focus:text-indigo-700"
                      title={`Edit user ${name}`}
                    >
                      {email}
                    </InertiaLink>
                  </td>
                  <td className="flex items-center px-6 py-4 border-t">
                    {roles.map(({ name, id }) => (
                      <InertiaLink
                        key={`role-${id}`}
                        tabIndex="-1"
                        href={route("roles.edit", id)}
                        className="px-2 focus:text-indigo-700 hover:text-indigo-700"
                        title={`Edit role ${name}`}
                      >
                        {name}
                      </InertiaLink>
                    ))}
                  </td>
                </tr>
              ))}
              {data.length === 0 && (
                <tr>
                  <td className="px-6 py-4 border-t" colSpan="3">
                    No users found.
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
        <Pagination links={links} />
      </div>
    </Layout>
  );
};
