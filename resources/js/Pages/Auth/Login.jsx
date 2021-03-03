import React, { useEffect, useState } from "react";
import Helmet from "react-helmet";
import { Inertia } from "@inertiajs/inertia";
import { InertiaLink, usePage } from "@inertiajs/inertia-react";
import { LoadingButton } from "@/Components/Buttons";
import { TextInput } from "@/Components/Inputs";
import {
  Box,
  Checkbox,
  Container,
  Flex,
  Heading,
  Input,
  Text,
  VStack,
} from "@chakra-ui/react";

export default () => {
  const { props } = usePage();
  const { errors } = props;

  const [sending, setSending] = useState(false);
  const [values, setValues] = useState({
    email: "johndoe@example.com",
    password: "password",
    remember: true,
  });

  function handleChange(e) {
    const key = e.target.name;
    const value =
      e.target.type === "checkbox" ? e.target.checked : e.target.value;

    setValues((values) => ({
      ...values,
      [key]: value,
    }));
  }

  function handleSubmit(e) {
    e.preventDefault();
    setSending(true);
    Inertia.post(route("login.attempt"), values);
  }

  return (
    <Flex minH="100vh" background="blue.500">
      <Container>
        <Helmet title="Login" />
        <Flex mt="24">
          <form
            onSubmit={handleSubmit}
            className="overflow-hidden bg-white rounded-lg shadow-xl"
          >
            <Box padding="8">
              <Heading>Welcome Back!</Heading>
              <Text mb="2">Email</Text>
              <Input
                variant="outline"
                value={values.email}
                onChange={handleChange}
                name="email"
              />
              <Text mb="1">Password</Text>
              <Input
                type="password"
                value={values.password}
                onChange={handleChange}
                name="password"
              />
              <Checkbox onChange={handleChange} name="remember">
                Remember me
              </Checkbox>
            </Box>
            <Flex flexDir="row" justify="space-between" background="gray.300" padding="4">
              <InertiaLink href="#forgot">Forget password?</InertiaLink>
              <LoadingButton
                type="submit"
                loading={sending}
                className="btn-indigo bg-primary"
              >
                Login
              </LoadingButton>
            </Flex>
          </form>
        </Flex>
      </Container>
    </Flex>
  );
};
