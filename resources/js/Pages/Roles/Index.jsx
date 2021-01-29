import React from 'react'
import Helmet from 'react-helmet'
import { InertiaLink, usePage } from '@inertiajs/inertia-react'
import { BaseLayout as Layout} from '@/Components/Layouts'
import Icon from '@/Components/Icon'
import Pagination from '@/Components/Pagination/Pagination'
import { Inertia } from '@inertiajs/inertia'

export default () => {
  const { roles } = usePage().props
  const { data, links } = roles

  return (
    <Layout>
      <div>
        <Helmet title="Roles" />
        <h1 className="mb-8 font-bold text-3xl">Roles</h1>
        <div className="bg-white roudned shadow overflow-x-auto">
          <table className="w-full whitespace-no-wrap">
            <thead>
              <tr className="text-left font-bold">
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
                      href={route('roles.edit', id)}
                      className="px-6 py-4 flex items-center focus:text-indigo-700"
                    >
                      {name}
                    </InertiaLink>
                  </td>
                </tr>
              ))}
              {data.length === 0 && (
                <tr>
                  <td className="border-t px-6 py-4" colSpan="4">
                    No roles found.
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </Layout>
  )
}
