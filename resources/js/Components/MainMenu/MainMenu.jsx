import React from 'react'
import MainMenuItem from '@/Components/MainMenu/MainMenuItem'

export default ({ className }) => {
  return (
    <div className={className}>
      <MainMenuItem text="Users" link="users.index" icon="users" />
      <MainMenuItem text="Roles" link="roles.index" icon="office" />
    </div>
  )
}
