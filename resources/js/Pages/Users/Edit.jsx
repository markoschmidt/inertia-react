import React, { useState } from 'react'
import Helmet from 'react-helmet'
import { InertiaLink, usePage } from '@inertiajs/inertia-react'
import { BaseLayout as Layout } from '@/Components/Layouts'
import { TextInput } from '@/Components/Inputs'
import { LoadingButton } from '@/Components/Buttons'
import { toFormData } from '@/utils'
import Icon from '@/Components/Icon'
import { Inertia } from '@inertiajs/inertia';

export default () => {
  const { user, errors } = usePage().props
  const [ sending, setSending ] = useState(false)
  const [ values, setValues] = useState({
    name: user.name,
    email: user.email,
    password: user.password,
    roles: user.roles,
  })

  function handleSubmit(e) {
    e.preventDefault()
    setSending(true)

    const formData = toFormData(values, 'PUT')

    Inertia.put(route('users.update', user.id), formData).then(() => {
      setSending(false)
    })
  }

  function handleChange(e) {
    const key = e.target.name
    const value = e.target.value

    setValues(values => ({
      ...values,
      [key]: value
    }))
  }

  return (
    <Layout>
      <div>
        <Helmet title={`User ${user.name}`} />
        <div className="flex justify-start max-w-lg mb-8">
          <h1 className="text-3xl font-bold">
            <InertiaLink
              href={route('users.index')}
              className="text-indigo-600 hover:text-indigo-700"
            >
              Users
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
              <TextInput
                className="w-full pb-8 pr-6 lg:w-1/2"
                label="Email"
                name="email"
                errors={errors.email}
                value={values.email}
                onChange={handleChange}
              />
              <TextInput
                className="w-full pb-8 pr-6 lg:w-1/2"
                label="Password"
                name="password"
                errors={errors.password}
                value={values.password}
                onChange={handleChange}
              />
            </div>
            <LoadingButton loading={sending} type="submit" className="ml-auto btn-indigo">Update User</LoadingButton>
          </form>
        </div>
      </div>
    </Layout>
  )
}
