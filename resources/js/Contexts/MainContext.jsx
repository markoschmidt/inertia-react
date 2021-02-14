import React, { createContext } from 'react';

export const MainContext = createContext();

class MainContextProvider extends React.Component {
  constructor(props) {
    super(props);

    this.toggleLocale = this.toggleLocale.bind(this)

    this.state = {
      locale: 'fi',
    }
  }

  toggleLocale() {
    this.setState({
      locale: this.state.locale === 'fi' ? 'en' : 'fi'
    })
  }

  render() {
    const value = {
      ...this.state,
      toggleLocale: this.toggleLocale,
    };

    return (
      <MainContext.Provider value={value}>
        {this.props.children}
      </MainContext.Provider>
    );
  }
}

export default MainContextProvider;
