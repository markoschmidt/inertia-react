import React, { createContext, useEffect, useState } from 'react';

export const MainContext = createContext();

const MainContextProvider = ({children}) => {

  const defaultLocale = localStorage.getItem('imagebank_locale');
  const [locale, setLocale] = useState(defaultLocale || 'fi');

  const toggleLocale = () => {
    setLocale(locale === 'fi' ? 'en' : 'fi')
  }

  useEffect(() => {
    localStorage.setItem('imagebank_locale', locale)
  }, [locale]);

  const value = {
    locale,
    toggleLocale
  }

  return (
    <MainContext.Provider value={value}>
      {children}
    </MainContext.Provider>
  )
}

export default MainContextProvider;
