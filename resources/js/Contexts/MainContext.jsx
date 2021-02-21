import React, { createContext, useState } from "react";

export const MainContext = createContext();

const MainContextProvider = ({ children }) => {
  const defaultLocale = localStorage.getItem("locale") || "fi";
  const [locale, setLocale] = useState(defaultLocale);

  const toggleLocale = () => {
    const newLocale = locale === "fi" ? "en" : "fi";
    setLocale(newLocale);
    localStorage.setItem("locale", newLocale);
  };

  const value = {
    locale,
    toggleLocale,
  };
  return <MainContext.Provider value={value}>{children}</MainContext.Provider>;
};

export default MainContextProvider;
