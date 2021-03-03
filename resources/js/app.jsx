import { InertiaApp } from '@inertiajs/inertia-react'
import React from 'react'
import { render } from 'react-dom'
import * as Sentry from '@sentry/browser';
import MainContextProvider from './Contexts/MainContext'
import { RecoilRoot } from 'recoil';
import { ChakraProvider } from '@chakra-ui/react'

Sentry.init({
  dns: process.env.MIX_SENTRY_LARAVEL_DSN
})

const app = document.getElementById('app')

render(
  <ChakraProvider>
      <MainContextProvider>
        <InertiaApp
          initialPage={JSON.parse(app.dataset.page)}
          resolveComponent={(name) =>
            import(`./Pages/${name}`).then((module) => module.default)
          }
        />
      </MainContextProvider>
  </ChakraProvider>,
  app
)
